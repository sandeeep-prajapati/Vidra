#!/usr/bin/env node
/**
 * generate-narration.js
 *
 * Generates a voiced narration MP4 from a documentation walkthrough spec.
 *
 * Usage:
 *   node scripts/generate-narration.js <spec-file> [video-webm] [options]
 *
 * Examples:
 *   node scripts/generate-narration.js tests/docs/class-management-walkthrough.spec.js
 *   node scripts/generate-narration.js tests/docs/class-management-walkthrough.spec.js \
 *       test-results/class-management.../video.webm
 *
 * Requirements (install once):
 *   pip3 install edge-tts          # free Microsoft Edge neural TTS
 *   apt install ffmpeg             # video/audio processing (already installed)
 *
 * Voice options (--voice flag):
 *   en-US-JennyNeural   (default, friendly female)
 *   en-US-GuyNeural     (male)
 *   en-IN-NeerjaNeural  (Indian English female)
 *   en-IN-PrabhatNeural (Indian English male)
 *   en-GB-SoniaNeural   (British English female)
 *
 * What it does:
 *   1. Parses all showCaption() calls + pause() durations from the spec
 *   2. Generates one MP3 per caption via edge-tts
 *   3. Builds a timed silent audio base track matching the video duration
 *   4. Overlays each caption audio at the correct timestamp
 *   5. If a video file is provided, merges audio + video into a final MP4
 */

import { execSync, spawnSync } from 'child_process';
import { readFileSync, mkdirSync, existsSync, readdirSync, statSync } from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(__dirname, '..');

// ── CLI args ──────────────────────────────────────────────────────────────────

const args = process.argv.slice(2);
if (args.length === 0 || args[0] === '--help') {
  console.log('Usage: node scripts/generate-narration.js <spec-file> [video.webm] [--voice <voice>] [--rate <0.5-2.0>]');
  process.exit(0);
}

let specFile = null;
let videoFile = null;
let voice = 'en-US-JennyNeural';
let rate = '+0%'; // edge-tts rate adjustment

for (let i = 0; i < args.length; i++) {
  if (args[i] === '--voice') { voice = args[++i]; }
  else if (args[i] === '--rate') { rate = args[++i]; }
  else if (args[i].endsWith('.spec.js')) { specFile = path.resolve(args[i]); }
  else if (args[i].endsWith('.webm') || args[i].endsWith('.mp4')) { videoFile = path.resolve(args[i]); }
}

if (!specFile) {
  console.error('Error: please provide a .spec.js file');
  process.exit(1);
}

// ── Dependency check ──────────────────────────────────────────────────────────

function checkDep(cmd, installHint) {
  for (const flag of ['-version', '--version']) {
    const r = spawnSync(cmd, [flag], { encoding: 'utf-8', shell: false });
    if (r.status === 0) return;
  }
  console.error(`Missing: ${cmd}\n  Install: ${installHint}`);
  process.exit(1);
}

checkDep('ffmpeg', 'sudo apt install ffmpeg');

// edge-tts may be installed in ~/.local/bin (pip install --user)
const EDGE_TTS = (() => {
  for (const candidate of ['edge-tts', `${process.env.HOME}/.local/bin/edge-tts`]) {
    const r = spawnSync('which', [candidate], { encoding: 'utf-8', shell: false });
    if (r.status === 0) return candidate;
    const r2 = spawnSync(candidate, ['--version'], { encoding: 'utf-8', shell: false });
    if (r2.status === 0) return candidate;
  }
  return null;
})();
if (!EDGE_TTS) {
  console.error('Missing: edge-tts\n  Install: pip3 install edge-tts --break-system-packages');
  process.exit(1);
}

// ── Parse spec file ───────────────────────────────────────────────────────────

const specName = path.basename(specFile, '.spec.js');
const specContent = readFileSync(specFile, 'utf-8');
const lines = specContent.split('\n');

/**
 * Walk through each line and build a timeline of captions with their
 * approximate start time (ms) based on accumulated pause/action delays.
 */
function parseTimeline(lines) {
  const timeline = [];
  let cursor = 0; // ms elapsed so far

  // Rough cost of each action type (ms)
  const FILL_COST = 700;
  const CLICK_COST = 1800;  // clickAndWait includes networkidle
  const SELECT_COST = 700;
  const HIGHLIGHT_COST = 400;

  for (const line of lines) {
    const trimmed = line.trim();

    // showCaption — record timestamp, then add the caption's own pause (900ms)
    const captionMatch = trimmed.match(/showCaption\(page,\s*['"`](.+?)['"`]\)/);
    if (captionMatch) {
      timeline.push({ text: captionMatch[1], startMs: cursor });
      cursor += 900;
      continue;
    }

    // explicit pause
    const pauseMatch = trimmed.match(/pause\(page,\s*(\d+)\)/);
    if (pauseMatch) {
      cursor += parseInt(pauseMatch[1], 10);
      continue;
    }

    // action helpers
    if (/await fillField/.test(trimmed)) { cursor += FILL_COST; continue; }
    if (/await selectField|await selectFirstOption|await selectLastOption/.test(trimmed)) {
      cursor += SELECT_COST; continue;
    }
    if (/await clickAndWait/.test(trimmed)) { cursor += CLICK_COST; continue; }
    if (/await highlightText|await highlightElement/.test(trimmed)) {
      cursor += HIGHLIGHT_COST; continue;
    }
  }

  return { timeline, totalMs: cursor };
}

const { timeline, totalMs } = parseTimeline(lines);

if (timeline.length === 0) {
  console.error('No showCaption() calls found in spec file.');
  process.exit(1);
}

console.log(`\nSpec: ${specName}`);
console.log(`Found ${timeline.length} captions, estimated duration: ${(totalMs / 1000).toFixed(1)}s`);
console.log(`Voice: ${voice}\n`);

// ── Output paths ──────────────────────────────────────────────────────────────

const outDir = path.join(ROOT, 'test-results', 'narration', specName);
mkdirSync(outDir, { recursive: true });

// ── Generate per-caption audio ────────────────────────────────────────────────

console.log('Generating caption audio clips...');

const clipFiles = [];

for (let i = 0; i < timeline.length; i++) {
  const { text, startMs } = timeline[i];
  const clipMp3 = path.join(outDir, `caption-${String(i).padStart(3, '0')}.mp3`);

  console.log(`  [${String(i + 1).padStart(2)}] ${(startMs / 1000).toFixed(1)}s — "${text.slice(0, 60)}${text.length > 60 ? '…' : ''}"`);

  execSync(
    `"${EDGE_TTS}" --voice "${voice}" --rate="${rate}" --text "${text.replace(/"/g, '\\"')}" --write-media "${clipMp3}"`,
    { stdio: 'pipe', shell: false }
  );

  clipFiles.push({ mp3: clipMp3, startMs });
}

// ── Build a timed audio track ─────────────────────────────────────────────────

console.log('\nBuilding timed narration track with ffmpeg...');

const videoDurationMs = videoFile
  ? (() => {
      const probe = execSync(
        `ffprobe -v error -show_entries format=duration -of csv=p=0 "${videoFile}"`,
        { encoding: 'utf-8' }
      ).trim();
      return Math.ceil(parseFloat(probe) * 1000);
    })()
  : totalMs + 3000;

const trackDurationS = (videoDurationMs / 1000).toFixed(3);
const silentBase = path.join(outDir, 'silent-base.wav');
const narrationWav = path.join(outDir, 'narration.wav');

// Generate a silent base track matching the video length
execSync(`ffmpeg -y -f lavfi -i anullsrc=r=44100:cl=stereo -t ${trackDurationS} "${silentBase}"`, { stdio: 'pipe' });

// Build ffmpeg amix command: overlay each clip at its timestamp
const inputs = [`-i "${silentBase}"`];
const filterParts = [`[0:a]adelay=0|0[base]`];
let mixInputs = ['[base]'];

for (let i = 0; i < clipFiles.length; i++) {
  const { mp3, startMs } = clipFiles[i];
  inputs.push(`-i "${mp3}"`);
  const delay = `${startMs}|${startMs}`;
  filterParts.push(`[${i + 1}:a]adelay=${delay}[clip${i}]`);
  mixInputs.push(`[clip${i}]`);
}

filterParts.push(`${mixInputs.join('')}amix=inputs=${mixInputs.length}:normalize=0[out]`);

const filterComplex = filterParts.join('; ');
const ffmpegCmd = `ffmpeg -y ${inputs.join(' ')} -filter_complex "${filterComplex}" -map "[out]" -t ${trackDurationS} "${narrationWav}"`;

execSync(ffmpegCmd, { stdio: 'pipe' });
console.log(`  Narration audio: ${narrationWav}`);

// ── Merge with video (if provided) ────────────────────────────────────────────

if (videoFile) {
  const outputMp4 = path.join(outDir, `${specName}-voiced.mp4`);
  console.log('\nMerging audio + video...');
  execSync(
    `ffmpeg -y -i "${videoFile}" -i "${narrationWav}" -c:v copy -c:a aac -shortest "${outputMp4}"`,
    { stdio: 'pipe' }
  );
  console.log(`\n✓ Final video with narration: ${outputMp4}`);
} else {
  console.log('\nNo video file provided. To merge narration with your Playwright video, run:');
  console.log(`  ffmpeg -i <video.webm> -i "${narrationWav}" -c:v copy -c:a aac -shortest output.mp4`);
  console.log('\nOr re-run with the video path:');
  console.log(`  node scripts/generate-narration.js ${path.relative(ROOT, specFile)} <video.webm>`);
}

console.log('\nDone.');
