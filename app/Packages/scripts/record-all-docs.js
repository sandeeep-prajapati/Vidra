#!/usr/bin/env node
/**
 * record-all-docs.js
 *
 * Records ALL documentation walkthrough videos in sequence.
 * Runs db:fresh only once at the start, then records each module
 * one by one, generating narration + music + fades for each.
 *
 * Usage:
 *   npm run record:all
 *   npm run record:all -- --voice en-IN-NeerjaNeural
 *   npm run record:all -- --music assets/background.mp3 --vol 0.1
 *
 * All flags are forwarded to record-doc.js (--voice, --music, --vol, --fade, --out).
 * Finished MP4s land in ./videos/
 */

import { execSync } from 'child_process';
import { existsSync, mkdirSync } from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT      = path.resolve(__dirname, '..');

// ── Specs to record (in logical order) ───────────────────────────────────────
const SPECS = [
  'attendance-management-walkthrough',
  'class-management-walkthrough',
  'student-management-walkthrough',
  'staff-management-walkthrough',
  'subject-management-walkthrough',
  'exam-management-walkthrough',
  'fee-management-walkthrough',
  'communication-management-walkthrough',
  'timetable-management-walkthrough',
  'hostel-transport-walkthrough',
  'rbac-management-walkthrough',
  'data-transfer-walkthrough',
  'webhook-walkthrough',
];

// Extra flags forwarded to record-doc.js (e.g. --voice, --music, --vol)
const extraFlags = process.argv.slice(2).join(' ');

// ── Fresh database once ───────────────────────────────────────────────────────
console.log('\n════════════════════════════════════════════════════');
console.log('  Record ALL Documentation Videos');
console.log('════════════════════════════════════════════════════');
console.log(`\n  Modules : ${SPECS.length}`);
if (extraFlags) console.log(`  Options : ${extraFlags}`);
console.log('');

console.log('▶  Setting up fresh database...\n');
execSync('bash scripts/fresh-migrate.sh', { cwd: ROOT, stdio: 'inherit' });

// ── Track results ─────────────────────────────────────────────────────────────
const results = [];
const startAll = Date.now();

for (let i = 0; i < SPECS.length; i++) {
  const spec     = SPECS[i];
  const specFile = `tests/docs/${spec}.spec.js`;
  const label    = `[${i + 1}/${SPECS.length}] ${spec}`;

  console.log(`\n${'─'.repeat(56)}`);
  console.log(`  ${label}`);
  console.log(`${'─'.repeat(56)}\n`);

  const startOne = Date.now();
  let status = 'ok';

  try {
    execSync(
      `node scripts/record-doc.js "${specFile}" ${extraFlags}`,
      { cwd: ROOT, stdio: 'inherit' }
    );
  } catch (err) {
    status = 'failed';
    console.error(`\n✗  ${spec} failed (see output above)\n`);
  }

  const elapsedMin = ((Date.now() - startOne) / 60000).toFixed(1);
  results.push({ spec, status, elapsedMin });
}

// ── Summary ───────────────────────────────────────────────────────────────────
const totalMin = ((Date.now() - startAll) / 60000).toFixed(1);
const passed   = results.filter(r => r.status === 'ok').length;
const failed   = results.filter(r => r.status === 'failed').length;

console.log('\n════════════════════════════════════════════════════');
console.log('  Summary');
console.log('════════════════════════════════════════════════════\n');

for (const { spec, status, elapsedMin } of results) {
  const icon = status === 'ok' ? '✓' : '✗';
  console.log(`  ${icon}  ${spec.padEnd(42)} ${elapsedMin}m`);
}

console.log('');
console.log(`  Passed : ${passed}/${SPECS.length}`);
if (failed) console.log(`  Failed : ${failed}/${SPECS.length}`);
console.log(`  Total  : ${totalMin} minutes`);
console.log(`\n  Videos → ${path.join(ROOT, 'videos')}/`);
console.log('════════════════════════════════════════════════════\n');

process.exit(failed > 0 ? 1 : 0);
