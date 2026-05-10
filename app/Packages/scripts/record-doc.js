#!/usr/bin/env node
/**
 * record-doc.js
 *
 * All-in-one: records a Playwright documentation walkthrough video,
 * generates voiced narration via edge-tts, adds background music,
 * adds fade-in/fade-out transitions, and outputs a polished MP4.
 *
 * Usage:
 *   node scripts/record-doc.js <spec-file> [options]
 *
 * Options:
 *   --voice  <voice>    TTS voice (default: en-US-JennyNeural)
 *   --rate   <rate>     TTS speed e.g. +10% or -5% (default: +0%)
 *   --music  <file>     Background music file (MP3/WAV). If omitted,
 *                       a built-in ambient track is generated.
 *   --vol    <0-1>      Background music volume (default: 0.12)
 *   --fade   <seconds>  Fade-in / fade-out duration (default: 1.5)
 *   --out    <dir>      Output directory (default: ./videos)
 *
 * Available voices:
 *   en-US-JennyNeural    (default — US English female)
 *   en-US-GuyNeural      (US English male)
 *   en-IN-NeerjaNeural   (Indian English female)
 *   en-IN-PrabhatNeural  (Indian English male)
 *   en-GB-SoniaNeural    (British English female)
 *
 * Examples:
 *   node scripts/record-doc.js tests/docs/class-management-walkthrough.spec.js
 *   node scripts/record-doc.js tests/docs/fee-management-walkthrough.spec.js \
 *       --voice en-IN-NeerjaNeural --music assets/background.mp3 --vol 0.1
 */

import { execSync, spawnSync } from 'child_process';
import {
  readFileSync, writeFileSync, mkdirSync, existsSync,
  readdirSync, statSync, rmSync,
} from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(__dirname, '..');

// ── CLI ───────────────────────────────────────────────────────────────────────

const args = process.argv.slice(2);
if (args.length === 0 || args[0] === '--help') {
  console.log('Usage: node scripts/record-doc.js <spec-file> [--voice V] [--music file] [--vol 0.12] [--fade 1.5] [--out dir]');
  process.exit(0);
}

let specFile = null;
let voice    = 'en-US-JennyNeural';
let rate     = '+0%';
let musicFile = null;
let musicVol  = 0.25;
let fadeSec   = 1.5;
let outDir    = path.join(ROOT, 'videos');

for (let i = 0; i < args.length; i++) {
  if      (args[i] === '--voice') { voice     = args[++i]; }
  else if (args[i] === '--rate')  { rate      = args[++i]; }
  else if (args[i] === '--music') { musicFile = path.resolve(args[++i]); }
  else if (args[i] === '--vol')   { musicVol  = parseFloat(args[++i]); }
  else if (args[i] === '--fade')  { fadeSec   = parseFloat(args[++i]); }
  else if (args[i] === '--out')   { outDir    = path.resolve(args[++i]); }
  else if (args[i].endsWith('.spec.js')) { specFile = path.resolve(args[i]); }
}

if (!specFile || !existsSync(specFile)) {
  console.error('Error: spec file not found:', specFile);
  process.exit(1);
}

const specName = path.basename(specFile, '.spec.js');

// ── Dependency check ──────────────────────────────────────────────────────────

function findCmd(candidates) {
  for (const cmd of candidates) {
    for (const flag of ['-version', '--version']) {
      const r = spawnSync(cmd, [flag], { encoding: 'utf-8', shell: false });
      if (r.status === 0) return cmd;
    }
  }
  return null;
}

const FFMPEG = findCmd(['ffmpeg']);
if (!FFMPEG) { console.error('Missing: ffmpeg\n  Install: sudo apt install ffmpeg'); process.exit(1); }

const EDGE_TTS = findCmd(['edge-tts', `${process.env.HOME}/.local/bin/edge-tts`]);
if (!EDGE_TTS) {
  console.error('Missing: edge-tts\n  Install: pip3 install edge-tts --break-system-packages');
  process.exit(1);
}

// ── Step 1: run Playwright test ────────────────────────────────────────────────

console.log('\n══════════════════════════════════════════════');
console.log(` Recording: ${specName}`);
console.log('══════════════════════════════════════════════\n');

// Remove stale test-results for this spec
const testResultsDir = path.join(ROOT, 'test-results');
if (existsSync(testResultsDir)) {
  const slug = specName.replace(/-walkthrough$/, '').replace(/-/g, '-').slice(0, 10);
  for (const entry of readdirSync(testResultsDir)) {
    if (entry.startsWith('docs-' + slug)) {
      rmSync(path.join(testResultsDir, entry), { recursive: true, force: true });
    }
  }
}

console.log('▶  Running Playwright test (this will take a few minutes)...\n');
try {
  execSync(
    `npx playwright test "${path.relative(ROOT, specFile)}"`,
    { cwd: ROOT, stdio: 'inherit', env: { ...process.env, PLAYWRIGHT_DOCS_VIDEO: '1' } }
  );
} catch { /* test may fail but video is still written */ }

// ── Step 2: locate the generated .webm ───────────────────────────────────────

function findNewestWebm(dir) {
  if (!existsSync(dir)) return null;
  let newest = null, newestTime = 0;
  function scan(d, depth) {
    if (depth > 4) return;
    for (const entry of readdirSync(d)) {
      const full = path.join(d, entry);
      if (entry === 'video.webm') {
        const t = statSync(full).mtimeMs;
        if (t > newestTime) { newestTime = t; newest = full; }
      } else {
        try { if (statSync(full).isDirectory()) scan(full, depth + 1); } catch { /* skip */ }
      }
    }
  }
  scan(dir, 0);
  return newest;
}

const webmFile = findNewestWebm(testResultsDir);
if (!webmFile) {
  console.error('\n✗  No video.webm found in test-results. Did the test run and the server was up?');
  if (existsSync(testResultsDir)) {
    console.error('    Contents:', readdirSync(testResultsDir).join(', ') || '(empty)');
  }
  process.exit(1);
}
console.log(`\n✓  Video recorded: ${webmFile}`);

// ── Step 3: parse caption timeline from spec ──────────────────────────────────

function parseTimeline(lines) {
  const timeline = [];
  let cursor = 0;
  const FILL = 700, CLICK = 1800, SELECT = 700, HIGHLIGHT = 400;
  for (const line of lines) {
    const t = line.trim();
    const caption = t.match(/showCaption\(page,\s*['"`](.+?)['"`]\)/);
    if (caption) { timeline.push({ text: caption[1], startMs: cursor }); cursor += 900; continue; }
    const pause = t.match(/pause\(page,\s*(\d+)\)/);
    if (pause) { cursor += parseInt(pause[1], 10); continue; }
    if (/await fillField/.test(t)) { cursor += FILL; continue; }
    if (/await select(Field|FirstOption|LastOption)/.test(t)) { cursor += SELECT; continue; }
    if (/await clickAndWait/.test(t)) { cursor += CLICK; continue; }
    if (/await highlight(Text|Element)/.test(t)) { cursor += HIGHLIGHT; continue; }
  }
  return timeline;
}

const lines    = readFileSync(specFile, 'utf-8').split('\n');
const timeline = parseTimeline(lines);
if (timeline.length === 0) { console.error('✗  No showCaption() calls found.'); process.exit(1); }

console.log(`\n▶  Generating narration for ${timeline.length} captions (voice: ${voice})...\n`);

// ── Step 4: generate per-caption audio ───────────────────────────────────────

const tmpDir = path.join(ROOT, 'test-results', 'narration', specName);
mkdirSync(tmpDir, { recursive: true });

const clipFiles = [];
for (let i = 0; i < timeline.length; i++) {
  const { text, startMs } = timeline[i];
  const clipMp3 = path.join(tmpDir, `cap-${String(i).padStart(3, '0')}.mp3`);
  process.stdout.write(`  [${String(i + 1).padStart(2)}/${timeline.length}] ${(startMs / 1000).toFixed(1)}s  "${text.slice(0, 55)}${text.length > 55 ? '…' : ''}"\n`);
  execSync(
    `"${EDGE_TTS}" --voice "${voice}" --rate="${rate}" --text "${text.replace(/"/g, '\\"')}" --write-media "${clipMp3}"`,
    { stdio: 'pipe', shell: false }
  );
  clipFiles.push({ mp3: clipMp3, startMs });
}

// ── Step 5: get video duration ────────────────────────────────────────────────

const probeDuration = execSync(
  `"${FFMPEG}" -v error -i "${webmFile}" -f null - 2>&1 | grep "time=" | tail -1 || ffprobe -v error -show_entries format=duration -of csv=p=0 "${webmFile}"`,
  { encoding: 'utf-8', shell: true }
).trim();

// Use ffprobe for reliable duration
const videoDurationSec = parseFloat(
  execSync(`ffprobe -v error -show_entries format=duration -of csv=p=0 "${webmFile}"`, { encoding: 'utf-8' }).trim()
);
const videoDurationMs = Math.ceil(videoDurationSec * 1000);
const trackS = videoDurationSec.toFixed(3);

// Scale narration timestamps so the last caption lands near the end of the video
const estimatedEndMs = timeline.length > 0
  ? timeline[timeline.length - 1].startMs + 900
  : videoDurationMs;
const scaleFactor = estimatedEndMs > 100 && estimatedEndMs < videoDurationMs * 1.5
  ? videoDurationMs / estimatedEndMs
  : 1.0;
if (Math.abs(scaleFactor - 1.0) > 0.05) {
  console.log(`\n  Narration sync: ×${scaleFactor.toFixed(2)} (est ${(estimatedEndMs / 1000).toFixed(0)}s → actual ${videoDurationSec.toFixed(0)}s)`);
}

// ── Step 6: build timed narration track ──────────────────────────────────────

console.log('\n▶  Mixing narration audio track...');

const silentBase    = path.join(tmpDir, 'silent.wav');
const narrationWav  = path.join(tmpDir, 'narration.wav');

execSync(`"${FFMPEG}" -y -f lavfi -i anullsrc=r=44100:cl=stereo -t ${trackS} "${silentBase}"`, { stdio: 'pipe' });

const inputs      = [`-i "${silentBase}"`];
const filterParts = [`[0:a]adelay=0|0[base]`];
const mixInputs   = ['[base]'];

for (let i = 0; i < clipFiles.length; i++) {
  const { mp3, startMs } = clipFiles[i];
  inputs.push(`-i "${mp3}"`);
  const scaledMs = Math.min(Math.round(startMs * scaleFactor), videoDurationMs - 1000);
  filterParts.push(`[${i + 1}:a]adelay=${scaledMs}|${scaledMs}[c${i}]`);
  mixInputs.push(`[c${i}]`);
}
filterParts.push(`${mixInputs.join('')}amix=inputs=${mixInputs.length}:normalize=0[narr]`);

execSync(
  `"${FFMPEG}" -y ${inputs.join(' ')} -filter_complex "${filterParts.join('; ')}" -map "[narr]" -t ${trackS} "${narrationWav}"`,
  { stdio: 'pipe' }
);

// ── Step 7: background music ──────────────────────────────────────────────────

console.log('▶  Preparing background music...');

const musicWav = path.join(tmpDir, 'music.wav');

if (musicFile && existsSync(musicFile)) {
  execSync(
    `"${FFMPEG}" -y -stream_loop -1 -i "${musicFile}" -t ${trackS} -af "afade=t=in:st=0:d=${fadeSec},afade=t=out:st=${(videoDurationSec - fadeSec).toFixed(2)}:d=${fadeSec}" "${musicWav}"`,
    { stdio: 'pipe' }
  );
  console.log(`  Using: ${path.basename(musicFile)}`);
} else {
  // 4-chord ambient pad (octave 3): Cmaj7 → Gadd9 → Am7 → Fmaj7
  const C3 = 130.81, D3 = 146.83, E3 = 164.81, F3 = 174.61;
  const G3 = 196.00, A3 = 220.00, B3 = 246.94;

  const chordExprs = [
    `0.28*sin(2*PI*${C3}*t)+0.22*sin(2*PI*${E3}*t)+0.18*sin(2*PI*${G3}*t)+0.14*sin(2*PI*${B3}*t)`,
    `0.28*sin(2*PI*${G3}*t)+0.22*sin(2*PI*${B3}*t)+0.18*sin(2*PI*${D3}*t)+0.14*sin(2*PI*${A3}*t)`,
    `0.28*sin(2*PI*${A3}*t)+0.22*sin(2*PI*${C3}*t)+0.18*sin(2*PI*${E3}*t)+0.14*sin(2*PI*${G3}*t)`,
    `0.28*sin(2*PI*${F3}*t)+0.22*sin(2*PI*${A3}*t)+0.18*sin(2*PI*${C3}*t)+0.14*sin(2*PI*${E3}*t)`,
  ];

  const chordDur = (videoDurationSec / chordExprs.length).toFixed(3);
  const chordWavs = [];

  for (let ci = 0; ci < chordExprs.length; ci++) {
    const cWav = path.join(tmpDir, `chord-${ci}.wav`);
    execSync(
      `"${FFMPEG}" -y -f lavfi -i "aevalsrc=${chordExprs[ci]}:s=44100" -t ${chordDur} "${cWav}"`,
      { stdio: 'pipe', shell: true }
    );
    chordWavs.push(cWav);
  }

  const listFile = path.join(tmpDir, 'chord-list.txt');
  writeFileSync(listFile, chordWavs.map(f => `file '${f}'`).join('\n'));

  const rawMusicWav = path.join(tmpDir, 'music-raw.wav');
  execSync(`"${FFMPEG}" -y -f concat -safe 0 -i "${listFile}" "${rawMusicWav}"`, { stdio: 'pipe' });

  execSync(
    `"${FFMPEG}" -y -i "${rawMusicWav}" -t ${trackS} \
      -af "afade=t=in:st=0:d=${(fadeSec * 2).toFixed(1)},afade=t=out:st=${(videoDurationSec - fadeSec).toFixed(2)}:d=${fadeSec}" \
      "${musicWav}"`,
    { stdio: 'pipe', shell: true }
  );

  console.log('  Using: ambient pad — Cmaj7 → Gadd9 → Am7 → Fmaj7');
}

// ── Step 8: merge video + narration + music → MP4 with fades ─────────────────

mkdirSync(outDir, { recursive: true });
const outputMp4 = path.join(outDir, `${specName}.mp4`);

console.log('▶  Rendering final MP4 (video + narration + music + fades)...');

/*
 * Video filter:  fade-in at start, fade-out at end
 * Audio filter:
 *   [narr] + [music at low vol] → amix → final
 * Video codec:   libx264 (H.264, wide compatibility)
 * CRF 18 = visually lossless
 */
const vFade  = `fade=t=in:st=0:d=${fadeSec},fade=t=out:st=${(videoDurationSec - fadeSec).toFixed(2)}:d=${fadeSec}`;
const aFade  = `afade=t=in:st=0:d=${fadeSec},afade=t=out:st=${(videoDurationSec - fadeSec).toFixed(2)}:d=${fadeSec}`;

execSync(
  `"${FFMPEG}" -y \
    -i "${webmFile}" \
    -i "${narrationWav}" \
    -i "${musicWav}" \
    -filter_complex \
      "[0:v]${vFade}[vout]; \
       [1:a]${aFade}[narr_faded]; \
       [2:a]loudnorm,volume=${musicVol}[music_low]; \
       [narr_faded][music_low]amix=inputs=2:normalize=0[aout]" \
    -map "[vout]" -map "[aout]" \
    -c:v libx264 -preset fast -crf 18 \
    -c:a aac -b:a 192k \
    -movflags +faststart \
    -shortest \
    "${outputMp4}"`,
  { stdio: 'inherit', shell: true }
);

// ── Done ──────────────────────────────────────────────────────────────────────

const sizeMb = (statSync(outputMp4).size / 1024 / 1024).toFixed(1);
console.log('\n══════════════════════════════════════════════');
console.log(` ✓  Done!  ${sizeMb} MB`);
console.log(` Voice:    ${voice}`);
console.log(` Music:    ${musicFile ? path.basename(musicFile) : 'built-in ambient'} (vol ${musicVol})`);
console.log(` Fades:    ${fadeSec}s in + ${fadeSec}s out`);
console.log(` Output:   ${outputMp4}`);
console.log('══════════════════════════════════════════════\n');
