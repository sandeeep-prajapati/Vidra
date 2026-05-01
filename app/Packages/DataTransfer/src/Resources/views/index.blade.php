@extends('core-package::layouts.app')

@section('title', 'Data Transfer — Import & Export')

@section('breadcrumb')
<div style="display:flex;align-items:center;gap:.5rem;font-size:.8125rem;color:#64748b;">
    <span style="color:#1e293b;font-weight:600;">Data Transfer</span>
</div>
@endsection

@section('content')
<style>
    /* ── Form controls ── */
    .dt-label { display:block; font-size:.75rem; font-weight:600; color:#374151; margin-bottom:.375rem; letter-spacing:.01em; }
    .dt-select, .dt-input {
        width:100%; padding:.625rem .875rem; border:1px solid #e2e8f0; border-radius:.5rem;
        font-size:.8125rem; color:#1e293b; background:#fff; transition:border-color .15s, box-shadow .15s;
        outline:none; appearance:none; -webkit-appearance:none;
    }
    .dt-select { background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%2394a3b8' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right .75rem center; padding-right:2.25rem; }
    .dt-select:focus, .dt-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
    .dt-hint { font-size:.7rem; color:#94a3b8; margin-top:.3rem; }

    /* ── Tab pills ── */
    .dt-tab { display:flex; align-items:center; gap:.5rem; padding:.5rem 1.125rem; border-radius:.5rem; font-size:.8125rem; font-weight:600; cursor:pointer; border:none; transition:background .15s, color .15s; }
    .dt-tab.active-import { background:#eef2ff; color:#4338ca; }
    .dt-tab.active-export { background:#ecfdf5; color:#065f46; }
    .dt-tab.inactive { background:transparent; color:#64748b; }
    .dt-tab.inactive:hover { background:#f8fafc; color:#1e293b; }

    /* ── Drop zone ── */
    .drop-zone {
        border:2px dashed #c7d2fe; border-radius:.75rem; padding:2rem 1.5rem;
        text-align:center; cursor:pointer; transition:border-color .2s, background .2s;
        background:#fafbff;
    }
    .drop-zone:hover, .drop-zone.dragover { border-color:#6366f1; background:#f0f1ff; }
    .drop-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }

    /* ── Submit btn ── */
    .dt-btn-import { width:100%; padding:.75rem; background:linear-gradient(135deg,#4f46e5,#6366f1); color:#fff; font-size:.875rem; font-weight:600; border:none; border-radius:.5rem; cursor:pointer; transition:opacity .15s, transform .1s; }
    .dt-btn-import:hover { opacity:.9; transform:translateY(-1px); }
    .dt-btn-export { width:100%; padding:.75rem; background:linear-gradient(135deg,#059669,#10b981); color:#fff; font-size:.875rem; font-weight:600; border:none; border-radius:.5rem; cursor:pointer; transition:opacity .15s, transform .1s; }
    .dt-btn-export:hover { opacity:.9; transform:translateY(-1px); }

    /* ── State badge ── */
    .state-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .625rem; border-radius:999px; font-size:.7rem; font-weight:600; white-space:nowrap; }
    .state-pending    { background:#fef3c7; color:#92400e; }
    .state-processing { background:#dbeafe; color:#1e40af; }
    .state-completed  { background:#dcfce7; color:#14532d; }
    .state-failed     { background:#fee2e2; color:#991b1b; }

    /* ── Stat mini card ── */
    .stat-chip { display:inline-flex; align-items:center; gap:.375rem; padding:.25rem .625rem; border-radius:.375rem; font-size:.7rem; font-weight:500; }

    /* ── Row hover ── */
    .dt-row { transition:background .12s; }
    .dt-row:hover { background:#f8fafc; }

    /* ── Panel ── */
    .dt-panel { background:#fff; border:1px solid #e2e8f0; border-radius:.875rem; overflow:hidden; }

    /* ── Info row ── */
    .info-row { display:flex; align-items:center; gap:.5rem; padding:.625rem .875rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:.5rem; font-size:.75rem; color:#64748b; }
</style>

{{-- ── Page header ── --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#0f172a;margin:0;">Data Transfer</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Bulk import or export students, staff, fees, and salary data via CSV</p>
    </div>
    <div style="display:flex;align-items:center;gap:.5rem;">
        <a href="{{ route('data-transfer.index') }}" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem .875rem;background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;font-size:.8125rem;font-weight:500;color:#64748b;text-decoration:none;transition:border-color .15s;" onmouseover="this.style.borderColor='#c7d2fe';this.style.color='#4338ca'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
            <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </a>
    </div>
</div>

{{-- ── Flash messages ── --}}
@if(session('success'))
<div style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:.625rem;margin-bottom:1.5rem;">
    <svg style="width:1.125rem;height:1.125rem;color:#16a34a;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p style="font-size:.8125rem;font-weight:500;color:#166534;margin:0;">{{ session('success') }}</p>
</div>
@endif
@if(session('error'))
<div style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:.625rem;margin-bottom:1.5rem;">
    <svg style="width:1.125rem;height:1.125rem;color:#dc2626;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p style="font-size:.8125rem;font-weight:500;color:#991b1b;margin:0;">{{ session('error') }}</p>
</div>
@endif
@if($errors->any())
<div style="display:flex;align-items:flex-start;gap:.75rem;padding:.875rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:.625rem;margin-bottom:1.5rem;">
    <svg style="width:1.125rem;height:1.125rem;color:#dc2626;flex-shrink:0;margin-top:.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        @foreach($errors->all() as $e)
            <p style="font-size:.8125rem;font-weight:500;color:#991b1b;margin:0 0 .125rem;">{{ $e }}</p>
        @endforeach
    </div>
</div>
@endif

{{-- ── Main two-column layout ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.5rem;">

    {{-- ━━━ IMPORT ━━━ --}}
    <div class="dt-panel">
        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:.75rem;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;">
            <div style="width:2.25rem;height:2.25rem;background:#eef2ff;border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1.125rem;height:1.125rem;color:#4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <div>
                <p style="font-size:.9375rem;font-weight:700;color:#0f172a;margin:0;">Import from CSV</p>
                <p style="font-size:.7rem;color:#94a3b8;margin:0;">Upload a CSV file to bulk-create or update records</p>
            </div>
        </div>

        <form action="{{ route('data-transfer.import') }}" method="POST" enctype="multipart/form-data" id="import-form" style="padding:1.5rem;">
            @csrf

            {{-- Entity type --}}
            <div style="margin-bottom:1.125rem;">
                <label class="dt-label" for="import-entity">
                    Entity Type
                    <span style="color:#ef4444;">*</span>
                </label>
                <select name="entity_type" id="import-entity" class="dt-select" required>
                    <option value="">Choose what to import…</option>
                    @foreach($importers as $key => $cfg)
                    <option value="{{ $key }}">{{ $cfg['title'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Drop zone --}}
            <div style="margin-bottom:1.125rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.375rem;">
                    <label class="dt-label" style="margin:0;">CSV File <span style="color:#ef4444;">*</span></label>
                    <a id="sample-link" href="#" style="display:none;font-size:.7rem;color:#4f46e5;text-decoration:none;font-weight:600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        ↓ Download sample CSV
                    </a>
                </div>
                <div class="drop-zone" id="drop-zone" style="position:relative;" onclick="document.getElementById('csv-file').click()">
                    <input type="file" id="csv-file" name="file" accept=".csv,.txt" style="display:none;" required>
                    <div id="drop-zone-idle">
                        <svg style="width:2rem;height:2rem;color:#a5b4fc;margin:0 auto .75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p style="font-size:.8125rem;color:#4f46e5;font-weight:600;margin:0;">Click to select or drag & drop</p>
                        <p style="font-size:.7rem;color:#94a3b8;margin:.25rem 0 0;">Supports .csv and .txt files</p>
                    </div>
                    <div id="drop-zone-selected" style="display:none;">
                        <svg style="width:1.5rem;height:1.5rem;color:#16a34a;margin:0 auto .5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p id="selected-filename" style="font-size:.8125rem;color:#0f172a;font-weight:600;margin:0;"></p>
                        <p id="selected-filesize" style="font-size:.7rem;color:#64748b;margin:.2rem 0 0;"></p>
                        <button type="button" onclick="clearFile(event)" style="margin-top:.5rem;font-size:.7rem;color:#94a3b8;background:none;border:none;cursor:pointer;text-decoration:underline;">Remove</button>
                    </div>
                </div>
            </div>

            {{-- Options row 1 --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;margin-bottom:1.125rem;">
                <div>
                    <label class="dt-label" for="action">Action</label>
                    <select name="action" id="action" class="dt-select">
                        <option value="append">Create / Update</option>
                        <option value="delete">Delete records</option>
                    </select>
                    <p class="dt-hint">How to handle rows from the file</p>
                </div>
                <div>
                    <label class="dt-label" for="field_separator">Field Separator</label>
                    <select name="field_separator" id="field_separator" class="dt-select">
                        <option value=",">, (comma)</option>
                        <option value=";">; (semicolon)</option>
                        <option value="|">| (pipe)</option>
                    </select>
                    <p class="dt-hint">Column delimiter in the file</p>
                </div>
            </div>

            {{-- Options row 2 --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;margin-bottom:1.5rem;">
                <div>
                    <label class="dt-label" for="validation_strategy">On Row Error</label>
                    <select name="validation_strategy" id="validation_strategy" class="dt-select">
                        <option value="skip-errors">Skip bad rows</option>
                        <option value="stop-on-errors">Stop on first error</option>
                    </select>
                    <p class="dt-hint">What to do when validation fails</p>
                </div>
                <div>
                    <label class="dt-label" for="allowed_errors">Max Allowed Errors</label>
                    <input type="number" name="allowed_errors" id="allowed_errors" class="dt-input" value="10" min="0" max="9999">
                    <p class="dt-hint">0 = no limit on skipped rows</p>
                </div>
            </div>

            <button type="submit" class="dt-btn-import" id="import-submit">
                <span style="display:flex;align-items:center;justify-content:center;gap:.5rem;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Start Import
                </span>
            </button>
        </form>
    </div>

    {{-- ━━━ EXPORT ━━━ --}}
    <div class="dt-panel">
        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:.75rem;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;">
            <div style="width:2.25rem;height:2.25rem;background:#ecfdf5;border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1.125rem;height:1.125rem;color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </div>
            <div>
                <p style="font-size:.9375rem;font-weight:700;color:#0f172a;margin:0;">Export to CSV</p>
                <p style="font-size:.7rem;color:#94a3b8;margin:0;">Download records as a CSV file with optional filters</p>
            </div>
        </div>

        <form action="{{ route('data-transfer.export') }}" method="POST" id="export-form" style="padding:1.5rem;">
            @csrf

            {{-- Entity type --}}
            <div style="margin-bottom:1.125rem;">
                <label class="dt-label" for="export-entity">
                    Entity Type
                    <span style="color:#ef4444;">*</span>
                </label>
                <select name="entity_type" id="export-entity" class="dt-select" required onchange="updateExportFilters(this.value)">
                    <option value="">Choose what to export…</option>
                    @foreach($exporters as $key => $cfg)
                    <option value="{{ $key }}">{{ $cfg['title'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Export info --}}
            <div class="info-row" id="export-tip" style="margin-bottom:1.125rem;">
                <svg style="width:.875rem;height:.875rem;flex-shrink:0;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Select an entity type to see available filters</span>
            </div>

            {{-- Filter: Status --}}
            <div id="filter-status" style="display:none;margin-bottom:1.125rem;">
                <label class="dt-label" for="status">Filter by Status</label>
                <select name="status" id="status" class="dt-select">
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="graduated">Graduated</option>
                    <option value="transferred">Transferred</option>
                </select>
            </div>

            {{-- Filter: Employment type --}}
            <div id="filter-employment" style="display:none;margin-bottom:1.125rem;">
                <label class="dt-label" for="employment_type">Filter by Employment Type</label>
                <select name="employment_type" id="employment_type" class="dt-select">
                    <option value="">All types</option>
                    <option value="full-time">Full Time</option>
                    <option value="part-time">Part Time</option>
                    <option value="contract">Contract</option>
                </select>
            </div>

            {{-- Filter: Date range --}}
            <div id="filter-dates" style="display:none;margin-bottom:1.125rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">
                    <div>
                        <label class="dt-label" for="from_date">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="dt-input">
                    </div>
                    <div>
                        <label class="dt-label" for="to_date">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="dt-input">
                    </div>
                </div>
            </div>

            {{-- Spacer so button stays at similar position --}}
            <div id="export-spacer" style="flex:1;"></div>

            <button type="submit" class="dt-btn-export" style="margin-top:auto;" id="export-submit">
                <span style="display:flex;align-items:center;justify-content:center;gap:.5rem;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Generate Export
                </span>
            </button>
        </form>
    </div>
</div>

{{-- ━━━ TRANSFER HISTORY ━━━ --}}
<div class="dt-panel">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1.125rem 1.5rem;border-bottom:1px solid #f1f5f9;">
        <div style="display:flex;align-items:center;gap:.75rem;">
            <div style="width:2rem;height:2rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1rem;height:1rem;color:#64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p style="font-size:.9375rem;font-weight:700;color:#0f172a;margin:0;">Transfer History</p>
                <p style="font-size:.7rem;color:#94a3b8;margin:0;">Recent import and export jobs</p>
            </div>
        </div>
        @if(!$jobs->isEmpty())
        <div style="display:flex;align-items:center;gap:.375rem;">
            <span id="poll-indicator" style="display:none;align-items:center;gap:.375rem;font-size:.7rem;color:#64748b;">
                <span style="width:.5rem;height:.5rem;background:#6366f1;border-radius:50%;animation:pulse-dot 1.5s ease-in-out infinite;"></span>
                Live
            </span>
        </div>
        @endif
    </div>

    @if($jobs->isEmpty())
    {{-- Empty state --}}
    <div style="padding:4rem 2rem;text-align:center;">
        <div style="width:3.5rem;height:3.5rem;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg style="width:1.5rem;height:1.5rem;color:#cbd5e1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p style="font-size:.9375rem;font-weight:600;color:#475569;margin:0;">No transfer jobs yet</p>
        <p style="font-size:.8125rem;color:#94a3b8;margin:.5rem 0 0;">Run your first import or export above to see history here.</p>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.75rem 1.25rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">Entity</th>
                    <th style="padding:.75rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Type</th>
                    <th style="padding:.75rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Action</th>
                    <th style="padding:.75rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                    <th style="padding:.75rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;min-width:160px;">Summary</th>
                    <th style="padding:.75rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Started</th>
                    <th style="padding:.75rem 1.25rem;text-align:right;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">File</th>
                </tr>
            </thead>
            <tbody id="jobs-tbody">
                @foreach($jobs as $job)
                @php
                    $track = $job->latestTrack;
                    $stateClass = match($track?->state) {
                        'pending'    => 'state-pending',
                        'processing' => 'state-processing',
                        'completed'  => 'state-completed',
                        'failed'     => 'state-failed',
                        default      => 'state-pending',
                    };
                @endphp
                <tr class="dt-row" data-track-id="{{ $track?->id }}" style="border-top:1px solid #f1f5f9;">
                    {{-- Entity --}}
                    <td style="padding:.875rem 1.25rem;">
                        <span style="font-weight:600;color:#1e293b;text-transform:capitalize;">{{ str_replace('_', ' ', $job->entity_type) }}</span>
                    </td>
                    {{-- Type badge --}}
                    <td style="padding:.875rem 1rem;">
                        @if($job->type === 'import')
                        <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;background:#eef2ff;color:#4338ca;border-radius:999px;font-size:.7rem;font-weight:600;">
                            <svg style="width:.625rem;height:.625rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Import
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .625rem;background:#ecfdf5;color:#065f46;border-radius:999px;font-size:.7rem;font-weight:600;">
                            <svg style="width:.625rem;height:.625rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export
                        </span>
                        @endif
                    </td>
                    {{-- Action --}}
                    <td style="padding:.875rem 1rem;color:#64748b;text-transform:capitalize;">{{ $job->action ?? '—' }}</td>
                    {{-- State --}}
                    <td style="padding:.875rem 1rem;">
                        @if($track)
                        <span class="state-badge {{ $stateClass }} track-state-badge" data-state="{{ $track->state }}">
                            @if($track->state === 'processing')
                            <svg style="width:.6rem;height:.6rem;animation:spin .8s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            @elseif($track->state === 'completed')
                            <svg style="width:.6rem;height:.6rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @elseif($track->state === 'failed')
                            <svg style="width:.6rem;height:.6rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                            <svg style="width:.6rem;height:.6rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                            {{ ucfirst($track->state) }}
                        </span>
                        @else
                        <span style="color:#cbd5e1;font-size:.75rem;">—</span>
                        @endif
                    </td>
                    {{-- Summary --}}
                    <td style="padding:.875rem 1rem;" class="track-summary-cell">
                        @if($track?->summary)
                        <div style="display:flex;flex-wrap:wrap;gap:.25rem;">
                            @foreach($track->summary as $k => $v)
                            @if(is_scalar($v))
                            @php
                                $chipColor = match(true) {
                                    str_contains(strtolower($k), 'success') || str_contains(strtolower($k), 'created') || str_contains(strtolower($k), 'exported') => 'background:#dcfce7;color:#14532d;',
                                    str_contains(strtolower($k), 'error') || str_contains(strtolower($k), 'fail') => 'background:#fee2e2;color:#991b1b;',
                                    str_contains(strtolower($k), 'skip') => 'background:#fef3c7;color:#92400e;',
                                    default => 'background:#f1f5f9;color:#475569;'
                                };
                            @endphp
                            <span class="stat-chip" style="{{ $chipColor }}">
                                {{ ucfirst(str_replace('_', ' ', $k)) }}: <strong>{{ $v }}</strong>
                            </span>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <span style="color:#cbd5e1;font-size:.75rem;">—</span>
                        @endif
                    </td>
                    {{-- Started --}}
                    <td style="padding:.875rem 1rem;color:#94a3b8;font-size:.75rem;white-space:nowrap;">
                        @if($track?->started_at)
                        <span title="{{ $track->started_at->format('Y-m-d H:i:s') }}">{{ $track->started_at->diffForHumans() }}</span>
                        @else
                        <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    {{-- Download --}}
                    <td style="padding:.875rem 1.25rem;text-align:right;">
                        @if($track?->output_file_path && $track?->state === 'completed')
                        <a href="{{ route('data-transfer.download', $track->id) }}"
                           style="display:inline-flex;align-items:center;gap:.375rem;padding:.375rem .75rem;background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;border-radius:.375rem;font-size:.7rem;font-weight:600;text-decoration:none;transition:background .15s;"
                           onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#ecfdf5'">
                            <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                        @else
                        <span style="color:#e2e8f0;font-size:.75rem;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
@keyframes pulse-dot { 0%,100%{ opacity:.6; transform:scale(1); } 50%{ opacity:1; transform:scale(1.3); } }
</style>

<script>
// ── File drop zone ──
const dropZone  = document.getElementById('drop-zone');
const fileInput = document.getElementById('csv-file');
const idle      = document.getElementById('drop-zone-idle');
const selected  = document.getElementById('drop-zone-selected');

function showFile(file) {
    if (!file) return;
    document.getElementById('selected-filename').textContent = file.name;
    document.getElementById('selected-filesize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    idle.style.display = 'none';
    selected.style.display = 'block';
}

fileInput.addEventListener('change', () => showFile(fileInput.files[0]));

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    const file = e.dataTransfer?.files?.[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        showFile(file);
    }
});

function clearFile(e) {
    e.stopPropagation();
    fileInput.value = '';
    idle.style.display = 'block';
    selected.style.display = 'none';
}

// ── Sample CSV link ──
document.getElementById('import-entity').addEventListener('change', function () {
    const link = document.getElementById('sample-link');
    if (this.value) {
        link.href = '{{ url("data-transfer/sample") }}/' + this.value;
        link.style.display = 'inline-flex';
    } else {
        link.style.display = 'none';
    }
});

// ── Export filters ──
function updateExportFilters(entity) {
    const tip    = document.getElementById('export-tip');
    const status = document.getElementById('filter-status');
    const empl   = document.getElementById('filter-employment');
    const dates  = document.getElementById('filter-dates');

    tip.style.display    = 'none';
    status.style.display = 'none';
    empl.style.display   = 'none';
    dates.style.display  = 'none';

    if (!entity) {
        tip.style.display = 'flex';
        return;
    }

    if (entity === 'student') {
        status.style.display = 'block';
    } else if (entity === 'staff') {
        status.style.display = 'block';
        empl.style.display   = 'block';
    } else if (entity === 'salary' || entity === 'student_fee') {
        dates.style.display = 'block';
    }
}

// ── Submit loading state ──
document.getElementById('import-form').addEventListener('submit', function () {
    const btn = document.getElementById('import-submit');
    btn.disabled = true;
    btn.innerHTML = '<span style="display:flex;align-items:center;justify-content:center;gap:.5rem;"><svg style="width:1rem;height:1rem;animation:spin .8s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Uploading…</span>';
});

document.getElementById('export-form').addEventListener('submit', function () {
    const btn = document.getElementById('export-submit');
    btn.disabled = true;
    btn.innerHTML = '<span style="display:flex;align-items:center;justify-content:center;gap:.5rem;"><svg style="width:1rem;height:1rem;animation:spin .8s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Processing…</span>';
});

// ── Live polling for pending/processing jobs ──
const pollIndicator = document.getElementById('poll-indicator');
let hasPending = false;

function pollPendingJobs() {
    const rows = document.querySelectorAll('[data-track-id]');
    hasPending = false;

    rows.forEach(row => {
        const badge   = row.querySelector('.track-state-badge');
        const state   = badge?.getAttribute('data-state');
        const trackId = row.getAttribute('data-track-id');

        if (!trackId || !badge || (state !== 'pending' && state !== 'processing')) return;
        hasPending = true;

        fetch(`/data-transfer/status/${trackId}`, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.state !== state) {
                    // State changed — reload the row area
                    location.reload();
                }
            }).catch(() => {});
    });

    if (pollIndicator) {
        pollIndicator.style.display = hasPending ? 'flex' : 'none';
    }
}

pollPendingJobs();
setInterval(pollPendingJobs, 5000);
</script>
@endsection
