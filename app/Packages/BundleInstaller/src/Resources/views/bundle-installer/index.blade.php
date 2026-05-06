@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Bundle Store</span>
</nav>
@endsection

@section('content')

<style>
.bs-header{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#4338ca 100%);border-radius:1rem;padding:2rem 2rem 1.75rem;margin-bottom:1.75rem;color:#fff;position:relative;overflow:hidden;}
.bs-header::before{content:'';position:absolute;top:-40px;right:-40px;width:220px;height:220px;background:rgba(255,255,255,.05);border-radius:50%;}
.bs-header::after{content:'';position:absolute;bottom:-60px;right:60px;width:160px;height:160px;background:rgba(255,255,255,.04);border-radius:50%;}
.bs-tabs{display:flex;gap:.25rem;background:#f1f5f9;border-radius:.625rem;padding:.25rem;margin-bottom:1.5rem;}
.bs-tab{flex:1;text-align:center;padding:.5rem 1rem;border-radius:.4rem;font-size:.8125rem;font-weight:600;cursor:pointer;border:none;background:transparent;color:#64748b;transition:all .15s;}
.bs-tab.active{background:#fff;color:#4f46e5;box-shadow:0 1px 3px rgba(0,0,0,.12);}
.bs-section-label{font-size:.65rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.875rem;}
.bs-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.125rem;margin-bottom:2rem;}
.bs-card{background:#fff;border-radius:.875rem;border:1px solid #e2e8f0;overflow:hidden;transition:box-shadow .2s,transform .15s;display:flex;flex-direction:column;}
.bs-card:hover{box-shadow:0 8px 24px rgba(0,0,0,.1);transform:translateY(-2px);}
.bs-card-top{padding:1.125rem 1.125rem .75rem;display:flex;gap:.875rem;align-items:flex-start;}
.bs-icon{width:3.25rem;height:3.25rem;border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.375rem;font-weight:800;color:#fff;letter-spacing:-.03em;}
.bs-card-meta{flex:1;min-width:0;}
.bs-card-name{font-size:.9375rem;font-weight:700;color:#1e293b;margin:0 0 .125rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.bs-card-author{font-size:.75rem;color:#64748b;}
.bs-card-body{padding:0 1.125rem .875rem;flex:1;}
.bs-card-desc{font-size:.8125rem;color:#475569;line-height:1.5;margin-bottom:.75rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.bs-features{display:flex;flex-wrap:wrap;gap:.375rem;}
.bs-feature-tag{font-size:.65rem;font-weight:600;background:#ede9fe;color:#5b21b6;padding:.2rem .5rem;border-radius:999px;}
.bs-card-footer{padding:.75rem 1.125rem 1rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:.5rem;}
.bs-version-badge{font-size:.6875rem;background:#f1f5f9;color:#64748b;padding:.2rem .5rem;border-radius:.25rem;font-weight:600;}
.bs-btn-install{padding:.4rem 1.1rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:.5rem;font-size:.8125rem;font-weight:700;cursor:pointer;letter-spacing:.01em;transition:opacity .15s;}
.bs-btn-install:hover{opacity:.88;}
.bs-btn-uninstall{padding:.4rem 1.1rem;background:transparent;color:#dc2626;border:1.5px solid #fca5a5;border-radius:.5rem;font-size:.8125rem;font-weight:700;cursor:pointer;transition:all .15s;}
.bs-btn-uninstall:hover{background:#fef2f2;border-color:#f87171;}
.bs-btn-open{padding:.4rem 1.1rem;background:#f1f5f9;color:#4f46e5;border:none;border-radius:.5rem;font-size:.8125rem;font-weight:700;cursor:pointer;transition:background .15s;text-decoration:none;display:inline-block;}
.bs-btn-open:hover{background:#e0e7ff;}
.bs-installed-dot{width:.5rem;height:.5rem;border-radius:50%;background:#10b981;flex-shrink:0;}
.bs-upload-zone{background:#fff;border:2px dashed #c7d2fe;border-radius:.875rem;padding:2rem;text-align:center;cursor:pointer;transition:border-color .15s,background .15s;}
.bs-upload-zone:hover,.bs-upload-zone.drag-over{border-color:#4f46e5;background:#faf5ff;}
.bs-empty{background:#f8fafc;border:1px dashed #e2e8f0;border-radius:.875rem;padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;}
.bs-stat{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:.875rem 1.25rem;display:flex;align-items:center;gap:.875rem;}
.bs-stat-num{font-size:1.5rem;font-weight:800;}
.bs-stat-label{font-size:.75rem;color:#64748b;font-weight:500;}
@media(max-width:640px){.bs-grid{grid-template-columns:1fr;}}
.bs-card-coming{background:linear-gradient(160deg,#fafbff 0%,#f5f3ff 100%);border:1px solid #e0e7ff;position:relative;overflow:hidden;}
.bs-card-coming::before{content:'';position:absolute;inset:0;background:repeating-linear-gradient(45deg,transparent,transparent 8px,rgba(99,102,241,.03) 8px,rgba(99,102,241,.03) 16px);pointer-events:none;}
.bs-coming-badge{display:inline-flex;align-items:center;gap:.3rem;font-size:.6rem;font-weight:700;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;padding:.2rem .55rem;border-radius:999px;letter-spacing:.04em;text-transform:uppercase;}
.bs-btn-soon{padding:.4rem 1.1rem;background:#ede9fe;color:#7c3aed;border:1.5px solid #c4b5fd;border-radius:.5rem;font-size:.8125rem;font-weight:700;cursor:default;opacity:.8;}
.bs-coming-divider{display:flex;align-items:center;gap:.75rem;margin:1.5rem 0 1.125rem;}
.bs-coming-divider-line{flex:1;height:1px;background:linear-gradient(90deg,#e0e7ff,transparent);}
.bs-coming-label{font-size:.65rem;font-weight:700;color:#8b5cf6;text-transform:uppercase;letter-spacing:.1em;white-space:nowrap;display:flex;align-items:center;gap:.35rem;}
.bs-icon-coming{opacity:.85;position:relative;}
.bs-coming-lock{position:absolute;bottom:-3px;right:-3px;width:1rem;height:1rem;background:#7c3aed;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #fff;}
.cs-search-wrap{display:flex;align-items:center;gap:.75rem;margin-bottom:1.125rem;}
.cs-search-inner{flex:1;position:relative;}
.cs-search-inner svg{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94a3b8;pointer-events:none;}
.cs-search-input{width:100%;padding:.55rem .75rem .55rem 2.375rem;border:1.5px solid #e2e8f0;border-radius:.625rem;font-size:.8125rem;color:#1e293b;background:#fff;outline:none;box-sizing:border-box;transition:border-color .15s;}
.cs-search-input:focus{border-color:#6366f1;}
.cs-pg-btn{padding:.35rem .625rem;border-radius:.375rem;border:1.5px solid #e2e8f0;background:#fff;color:#64748b;font-size:.75rem;font-weight:600;cursor:pointer;min-width:2rem;transition:all .15s;}
.cs-pg-btn:hover:not(:disabled){background:#f5f3ff;border-color:#c4b5fd;color:#6366f1;}
.cs-pg-btn.active{background:#6366f1;border-color:#6366f1;color:#fff;cursor:default;}
.cs-pg-btn:disabled{opacity:.35;cursor:default;}
</style>

{{-- ── Header ── --}}
<div class="bs-header">
    <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.375rem;">
                <div style="width:2.25rem;height:2.25rem;background:rgba(255,255,255,.15);border-radius:.625rem;display:flex;align-items:center;justify-content:center;">
                    <svg style="width:1.25rem;height:1.25rem;color:#fff;" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                </div>
                <h1 style="font-size:1.375rem;font-weight:800;margin:0;color:#fff;">Vidra Bundle Store</h1>
            </div>
            <p style="margin:0;color:rgba(255,255,255,.7);font-size:.8125rem;">Install and manage feature bundles for your school platform</p>
        </div>
        <div style="display:flex;gap:.625rem;">
            <div class="bs-stat" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15);">
                <div class="bs-stat-num" style="color:#fff;">{{ count($installed) }}</div>
                <div class="bs-stat-label" style="color:rgba(255,255,255,.7);">Installed</div>
            </div>
            <div class="bs-stat" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15);">
                <div class="bs-stat-num" style="color:#fff;">{{ count($extracted) }}</div>
                <div class="bs-stat-label" style="color:rgba(255,255,255,.7);">Available</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Alerts ── --}}
@if(session('success'))
<div style="display:flex;align-items:center;gap:.75rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.625rem;padding:.875rem 1rem;margin-bottom:1.25rem;">
    <svg style="width:1.125rem;height:1.125rem;color:#16a34a;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    <span style="font-size:.875rem;color:#15803d;font-weight:500;">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div style="display:flex;align-items:center;gap:.75rem;background:#fef2f2;border:1px solid #fecaca;border-radius:.625rem;padding:.875rem 1rem;margin-bottom:1.25rem;">
    <svg style="width:1.125rem;height:1.125rem;color:#dc2626;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span style="font-size:.875rem;color:#dc2626;font-weight:500;">{{ session('error') }}</span>
</div>
@endif

{{-- ── Tabs ── --}}
<div class="bs-tabs" id="bs-tabs">
    <button class="bs-tab active" onclick="switchTab('installed')">
        Installed
        @if(count($installed) > 0)<span style="background:#4f46e5;color:#fff;border-radius:999px;padding:.1rem .5rem;font-size:.65rem;margin-left:.375rem;">{{ count($installed) }}</span>@endif
    </button>
    <button class="bs-tab" onclick="switchTab('available')">
        Available
        @if(count($extracted) > 0)<span style="background:#64748b;color:#fff;border-radius:999px;padding:.1rem .5rem;font-size:.65rem;margin-left:.375rem;">{{ count($extracted) }}</span>@endif
    </button>
    <button class="bs-tab" onclick="switchTab('upload')">
        Upload Bundle
    </button>
</div>

{{-- ── Tab: Installed ── --}}
<div id="tab-installed">
    <div class="bs-section-label">Installed Bundles</div>

    @if(count($installed) === 0)
    <div class="bs-empty">
        <svg style="width:2.5rem;height:2.5rem;color:#cbd5e1;margin:0 auto .75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        No bundles installed yet. Go to <strong>Available</strong> or <strong>Upload Bundle</strong> to add one.
    </div>
    @else
    <div class="bs-grid">
        @foreach($installed as $bundle)
        @php $color = bundleColor($bundle['name']); @endphp
        <div class="bs-card">
            <div class="bs-card-top">
                <div class="bs-icon" style="background:linear-gradient(135deg,{{ $color[0] }},{{ $color[1] }});">
                    {{ strtoupper(substr($bundle['name'],0,2)) }}
                </div>
                <div class="bs-card-meta">
                    <div class="bs-card-name">{{ $bundle['name'] }}</div>
                    <div class="bs-card-author">{{ $bundle['author'] ?: 'Vidra' }}</div>
                    <div style="display:flex;align-items:center;gap:.375rem;margin-top:.25rem;">
                        <div class="bs-installed-dot"></div>
                        <span style="font-size:.6875rem;color:#059669;font-weight:600;">Installed</span>
                    </div>
                </div>
            </div>
            <div class="bs-card-body">
                <div class="bs-card-desc">{{ $bundle['description'] ?: 'No description provided.' }}</div>
                @if(!empty($bundle['features']))
                <div class="bs-features">
                    @foreach(array_slice($bundle['features'], 0, 4) as $feature)
                    <span class="bs-feature-tag">{{ $feature }}</span>
                    @endforeach
                    @if(count($bundle['features']) > 4)
                    <span class="bs-feature-tag" style="background:#f1f5f9;color:#64748b;">+{{ count($bundle['features'])-4 }} more</span>
                    @endif
                </div>
                @endif
            </div>
            <div class="bs-card-footer">
                <span class="bs-version-badge">v{{ $bundle['version'] }}</span>
                <div style="display:flex;gap:.5rem;">
                    @if($bundle['main_route'])
                    <a href="{{ $bundle['main_route'] }}" class="bs-btn-open">Open</a>
                    @endif
                    <form method="POST"
                          action="{{ route('bundle-installer.destroy', ['bundle' => $bundle['package']]) }}"
                          onsubmit="return confirmUninstall('{{ $bundle['name'] }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bs-btn-uninstall">Uninstall</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── Tab: Available ── --}}
<div id="tab-available" style="display:none;">
    <div class="bs-section-label">Available (Uploaded, Not Installed)</div>

    @if(count($extracted) === 0)
    <div class="bs-empty">
        <svg style="width:2.5rem;height:2.5rem;color:#cbd5e1;margin:0 auto .75rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
        No uploaded bundles waiting to be installed. Go to <strong>Upload Bundle</strong> to add one.
    </div>
    @else
    <div class="bs-grid">
        @foreach($extracted as $bundle)
        @php $color = bundleColor($bundle['name']); @endphp
        <div class="bs-card">
            <div class="bs-card-top">
                <div class="bs-icon" style="background:linear-gradient(135deg,{{ $color[0] }},{{ $color[1] }});opacity:.75;">
                    {{ strtoupper(substr($bundle['name'],0,2)) }}
                </div>
                <div class="bs-card-meta">
                    <div class="bs-card-name">{{ $bundle['name'] }}</div>
                    <div class="bs-card-author">{{ $bundle['author'] ?: 'Vidra' }}</div>
                    <div style="display:flex;align-items:center;gap:.375rem;margin-top:.25rem;">
                        <div style="width:.5rem;height:.5rem;border-radius:50%;background:#f59e0b;flex-shrink:0;"></div>
                        <span style="font-size:.6875rem;color:#b45309;font-weight:600;">Not Installed</span>
                    </div>
                </div>
            </div>
            <div class="bs-card-body">
                <div class="bs-card-desc">{{ $bundle['description'] ?: 'No description provided.' }}</div>
                @if(!empty($bundle['features']))
                <div class="bs-features">
                    @foreach(array_slice($bundle['features'], 0, 4) as $feature)
                    <span class="bs-feature-tag">{{ $feature }}</span>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="bs-card-footer">
                <span class="bs-version-badge">v{{ $bundle['version'] }}</span>
                <div style="display:flex;gap:.5rem;">
                    <form method="POST"
                          action="{{ route('bundle-installer.install', ['bundle' => $bundle['package']]) }}">
                        @csrf
                        <button type="submit" class="bs-btn-install">
                            <svg style="width:.75rem;height:.75rem;display:inline;vertical-align:middle;margin-right:.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Install
                        </button>
                    </form>
                    @php $removeName = addslashes($bundle['name']); @endphp
                    <form method="POST"
                          action="{{ route('bundle-installer.remove-files', ['bundle' => $bundle['package']]) }}"
                          onsubmit="return confirm('Delete all source files for {{ $removeName }}? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bs-btn-uninstall">Remove</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Coming Soon: JS-rendered, searchable, paginated ── --}}
    <div class="bs-coming-divider">
        <div class="bs-coming-divider-line" style="background:linear-gradient(270deg,#e0e7ff,transparent);"></div>
        <div class="bs-coming-label">
            <svg style="width:.75rem;height:.75rem;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
            Coming Soon — Upcoming Bundles
            <span id="cs-total-badge" style="background:#6366f1;color:#fff;border-radius:999px;padding:.1rem .5rem;font-size:.6rem;font-weight:700;"></span>
        </div>
        <div class="bs-coming-divider-line"></div>
    </div>

    <div class="cs-search-wrap">
        <div class="cs-search-inner">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input id="cs-search" class="cs-search-input" type="text" placeholder="Search upcoming bundles by name or feature…">
        </div>
        <div id="cs-count" style="font-size:.75rem;color:#64748b;white-space:nowrap;font-weight:500;"></div>
    </div>

    <div id="cs-grid" class="bs-grid"></div>

    <div id="cs-empty" class="bs-empty" style="display:none;">
        <svg style="width:2rem;height:2rem;color:#cbd5e1;margin:0 auto .625rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        No upcoming bundles match your search.
    </div>

    <div id="cs-pagination" style="display:flex;align-items:center;justify-content:center;gap:.375rem;padding:.75rem 0 1.75rem;flex-wrap:wrap;"></div>
</div>

{{-- ── Tab: Upload ── --}}
<div id="tab-upload" style="display:none;">
    <div class="bs-section-label">Upload a New Bundle</div>

    <div style="max-width:640px;">
        <form action="{{ route('bundle-installer.upload') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div class="bs-upload-zone" id="drop-zone" onclick="document.getElementById('bundle-file').click()">
                <input type="file" id="bundle-file" name="bundle" accept=".zip" style="display:none;"
                       onchange="handleFileSelect(this)">
                <svg style="width:2.5rem;height:2.5rem;color:#a5b4fc;margin:0 auto .875rem;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                <div id="drop-text" style="font-size:.9375rem;font-weight:600;color:#4f46e5;margin-bottom:.375rem;">
                    Drop your bundle ZIP here
                </div>
                <div style="font-size:.8125rem;color:#94a3b8;">or click to browse · Max 100 MB</div>
            </div>

            <div id="file-preview" style="display:none;margin-top:1rem;background:#fff;border:1px solid #e2e8f0;border-radius:.625rem;padding:1rem;display:flex;align-items:center;gap:.875rem;">
                <div style="width:2.5rem;height:2.5rem;background:#ede9fe;border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:1.25rem;height:1.25rem;color:#7c3aed;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                </div>
                <div style="flex:1;">
                    <div id="file-name" style="font-weight:600;color:#1e293b;font-size:.875rem;"></div>
                    <div id="file-size" style="font-size:.75rem;color:#64748b;"></div>
                </div>
                <button type="submit" class="bs-btn-install" style="padding:.5rem 1.5rem;font-size:.875rem;">
                    Upload & Extract
                </button>
            </div>
        </form>

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.875rem;padding:1.25rem;margin-top:1.25rem;">
            <div style="font-weight:700;color:#1e293b;font-size:.875rem;margin-bottom:.875rem;display:flex;align-items:center;gap:.5rem;">
                <svg style="width:1rem;height:1rem;color:#4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Bundle ZIP Structure
            </div>
            <pre style="background:#0f172a;color:#e2e8f0;padding:1rem;border-radius:.5rem;font-size:.725rem;line-height:1.7;margin:0;overflow-x:auto;">MyBundle/               ← root folder (or flat)
├── manifest.json       ← required
└── src/
    ├── Providers/
    │   └── MyBundleServiceProvider.php
    ├── Controllers/
    ├── Models/
    ├── Database/
    │   └── migrations/
    ├── Routes/
    │   └── web.php
    └── Views/

<span style="color:#94a3b8;">manifest.json fields:</span>
  name, version, description, author
  provider_class, package_path
  setup_route, main_route
  permissions_module, bundle_roles[]</pre>
        </div>
    </div>
</div>

<script>
// ── Tab switching ──────────────────────────────────────────
function switchTab(tab) {
    ['installed','available','upload'].forEach(t => {
        document.getElementById('tab-'+t).style.display = t === tab ? '' : 'none';
    });
    document.querySelectorAll('.bs-tab').forEach((el, i) => {
        const tabs = ['installed','available','upload'];
        el.classList.toggle('active', tabs[i] === tab);
    });
}

@if(count($installed) === 0 && count($extracted) > 0)
switchTab('available');
@endif

// ── File drop zone ────────────────────────────────────────
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.name.endsWith('.zip')) {
        document.getElementById('bundle-file').files = e.dataTransfer.files;
        showFilePreview(file);
    }
});
function handleFileSelect(input) { if (input.files[0]) showFilePreview(input.files[0]); }
function showFilePreview(file) {
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = (file.size/1024/1024).toFixed(2)+' MB';
    document.getElementById('drop-text').textContent = 'File selected ✓';
    document.getElementById('file-preview').style.display = 'flex';
}

// ── Uninstall confirm ─────────────────────────────────────
function confirmUninstall(name) {
    return confirm('Uninstall "'+name+'"?\n\nThis will:\n  • Drop all database tables\n  • Remove all permissions and roles\n  • Remove the service provider registration\n\nSource files are kept on disk.');
}

// ── Coming Soon: search + pagination ─────────────────────
const CS_PER_PAGE = 12;

const csData = [
    {n:'Event Management',i:'EV',d:'Plan school events — annual days, sports days, cultural fests, and field trips. Covers registration, RSVP, attendance, and post-event reporting.',f:['Event Creation','RSVP Tracking','Participant Registration','Event Calendar','Post-event Reports'],g:['#f59e0b','#d97706']},
    {n:'Grievance Management',i:'GR',d:'A structured ticketing system for students, parents, and staff to raise complaints with full status tracking, SLA-based escalation, and audit trail.',f:['Complaint Submission','Status Tracking','SLA Escalation','Anonymous Mode','Resolution Reports'],g:['#ef4444','#dc2626']},
    {n:'Online Admission',i:'OA',d:'Public-facing admission portal — multi-step application, document upload, review workflow, entrance test scheduling, and auto student record creation on acceptance.',f:['Online Application','Document Upload','Review Workflow','Entrance Test','Auto Student Creation'],g:['#0ea5e9','#0284c7']},
    {n:'Discipline Management',i:'DM',d:'Track student behavior incidents, manage a behavior point system, issue warnings or suspensions, and notify parents automatically with a full conduct history.',f:['Incident Logging','Behavior Points','Warning / Suspension','Parent Notifications','Conduct Reports'],g:['#8b5cf6','#7c3aed']},
    {n:'Clubs & Activities',i:'CA',d:'Manage school clubs, sports teams, and co-curricular activities. Track memberships, practice sessions, achievements, and inter-school competition results.',f:['Club Catalog','Membership Tracking','Session Scheduling','Achievement Records','Competition Results'],g:['#10b981','#059669']},
    {n:'Homework & Assignments',i:'HW',d:'Teachers create assignments for classes, students submit digitally, teachers grade and give feedback — with a full assignment calendar and late submission tracking.',f:['Assignment Creation','Digital Submission','Grading & Feedback','Late Tracking','Assignment Calendar'],g:['#6366f1','#4f46e5']},
    {n:'PTM Management',i:'PT',d:'Schedule parent-teacher meetings with teacher-defined slots and parent self-booking. Provides reminders, meeting notes, and slot fill-rate reports.',f:['PTM Events','Slot Definition','Parent Self-booking','Reminders','Meeting Notes'],g:['#14b8a6','#0d9488']},
    {n:'Inventory & Assets',i:'IA',d:'Track all school assets — furniture, lab equipment, sports gear, and IT hardware. Covers procurement, department allocation, maintenance schedules, and disposal.',f:['Asset Catalog','Stock Tracking','Allocation','Maintenance Schedule','Low-stock Alerts'],g:['#f97316','#ea580c']},
    {n:'Reports & Analytics',i:'RA',d:'Central reporting dashboard aggregating data from all modules — pre-built reports, custom report builder, PDF/Excel export, and scheduled email delivery.',f:['Role-based Dashboard','Pre-built Reports','Custom Builder','Export PDF / Excel','Scheduled Reports'],g:['#ec4899','#db2777']},
    {n:'AI Insights',i:'AI',d:'Neural-network powered analytics — student learning gap analysis, attendance pattern detection, performance prediction, and smart early-warning alerts for teachers.',f:['Learning Gap Analysis','Performance Prediction','Attendance Patterns','Smart Alerts','Batch AI Jobs'],g:['#a855f7','#6366f1']},
    {n:'Bus Route & GPS',i:'BR',d:'Enhanced transport management with real-time GPS tracking, route optimization, driver assignment, and parent notifications for bus arrival and departure.',f:['GPS Tracking','Route Optimization','Driver Management','Parent Alerts','Trip History'],g:['#06b6d4','#0891b2']},
    {n:'Health & Medical',i:'HM',d:'Maintain student and staff health records, track vaccinations, manage clinic visits, issue medical leave, and alert parents for health incidents.',f:['Health Records','Vaccination Tracking','Clinic Visits','Medical Leave','Health Alerts'],g:['#f43f5e','#e11d48']},
    {n:'Audit Log',i:'AL',d:'Complete audit trail for all user actions across the platform — who changed what and when, with filters by module, user, and date range for compliance.',f:['Action Logging','User Tracking','Module Filters','Date Range Search','Export Logs'],g:['#64748b','#475569']},
    {n:'Notifications Hub',i:'NH',d:'Centralized notification engine — send in-app, email, SMS, and push notifications with templates, scheduling, and delivery receipts across all modules.',f:['In-app Notifications','Email Templates','SMS Gateway','Push Notifications','Delivery Receipts'],g:['#eab308','#ca8a04']},
    {n:'OCR & Document Scan',i:'OC',d:'Extract text from uploaded documents, ID cards, and marksheets using OCR — auto-fill student records from scanned certificates and previous school documents.',f:['OCR Extraction','ID Card Scan','Marksheet Import','Auto-fill Records','Batch Processing'],g:['#78716c','#57534e']},
    {n:'Data Import & Export',i:'DI',d:'Bulk import students, staff, fees, and marks from Excel or CSV. Export any module data with custom field selection and scheduled automated exports.',f:['Excel / CSV Import','Bulk Student Import','Custom Export','Scheduled Exports','Import Validation'],g:['#2563eb','#1d4ed8']},
    {n:'Blockchain Certificates',i:'BC',d:'Issue tamper-proof academic certificates on a blockchain — students and employers can verify authenticity instantly with a QR code or certificate hash.',f:['Certificate Issuance','Blockchain Hashing','QR Verification','Employer Portal','Batch Issuance'],g:['#7c3aed','#5b21b6']},
    {n:'IoT Integration',i:'IO',d:'Connect smart classroom devices — IoT-based attendance via RFID cards, smart lockers, environment sensors, and energy monitoring across school buildings.',f:['RFID Attendance','Smart Lockers','Environment Sensors','Energy Monitoring','Device Dashboard'],g:['#059669','#047857']},
    {n:'Payroll Management',i:'PY',d:'Automate staff payroll — salary calculation with allowances and deductions, payslip generation, statutory compliance (PF/ESI), and bank transfer export.',f:['Salary Calculation','Allowances & Deductions','Payslip Generation','PF / ESI Compliance','Bank Export'],g:['#16a34a','#15803d']},
    {n:'Leave Management',i:'LM',d:'Staff leave application and approval workflow — casual leave, sick leave, and earned leave tracking with accrual, carry-forward, and calendar view.',f:['Leave Application','Approval Workflow','Leave Accrual','Carry-forward','Leave Calendar'],g:['#0d9488','#0f766e']},
    {n:'Scholarship Management',i:'SM',d:'Manage scholarship schemes — application portal, eligibility verification, approval workflow, disbursement tracking, and renewal alerts for recipients.',f:['Scholarship Schemes','Online Application','Eligibility Check','Disbursement Tracking','Renewal Alerts'],g:['#d97706','#b45309']},
    {n:'E-Learning / LMS',i:'EL',d:'Built-in Learning Management System — course creation, video lessons, quizzes, student progress tracking, and completion certificates inside the school portal.',f:['Course Builder','Video Lessons','Quizzes & Tests','Progress Tracking','Completion Certificates'],g:['#3b82f6','#2563eb']},
    {n:'Certificate Generator',i:'CG',d:'Generate and bulk-print custom certificates — bonafide, character, transfer, achievement, and participation certificates with configurable templates and digital signatures.',f:['Certificate Templates','Bulk Generation','Digital Signature','QR Verification','Print-ready PDF'],g:['#f59e0b','#d97706']},
    {n:'Document Management',i:'DC',d:'Centralized document store for student, staff, and school documents — version control, access control, and expiry alerts for licenses and time-sensitive records.',f:['Document Store','Version Control','Access Control','Expiry Alerts','Full-text Search'],g:['#94a3b8','#64748b']},
    {n:'Parent Portal',i:'PP',d:'Dedicated parent dashboard — view child attendance, marks, fee dues, homework, and timetable. Two-way messaging with teachers and instant school notifications.',f:['Attendance View','Marks & Results','Fee Status','Homework Tracking','Teacher Messaging'],g:['#a78bfa','#8b5cf6']},
    {n:'Student Portfolio',i:'SP',d:'Digital portfolio for each student — academics, sports, arts, and community service achievements compiled automatically into a shareable profile page.',f:['Academic Record','Achievement Gallery','Skills Tracking','Shareable Profile','Portfolio Export'],g:['#818cf8','#6366f1']},
    {n:'Canteen Management',i:'CN',d:'School canteen order management — digital menu, pre-order by students or parents, daily sales tracking, low-stock alerts, and nutritional information display.',f:['Digital Menu','Pre-order System','Daily Sales Report','Low-stock Alerts','Nutritional Info'],g:['#fb923c','#f97316']},
    {n:'Sports Management',i:'SG',d:'Track school sports teams, fixtures, match results, player stats, and tournament registrations. Manage sports equipment inventory and record achievements.',f:['Team Management','Fixture Scheduling','Match Results','Player Stats','Equipment Inventory'],g:['#22c55e','#16a34a']},
    {n:'Counseling Management',i:'CO',d:'Schedule and track student counseling sessions, maintain confidential session notes, manage referrals, and generate anonymized outcome reports for administration.',f:['Session Scheduling','Confidential Notes','Referral Tracking','Outcome Reports','Parent Consent'],g:['#fb7185','#f43f5e']},
    {n:'Visitor Management',i:'VM',d:'Log and manage all school visitors — pre-approved visits, ID verification, visitor badges, entry/exit tracking, and alerts for unscheduled or suspicious visitors.',f:['Visitor Registration','ID Verification','Badge Printing','Entry / Exit Log','Unscheduled Alerts'],g:['#9ca3af','#6b7280']},
    {n:'ID Card Generator',i:'ID',d:'Design and bulk-generate student and staff ID cards with custom templates, photos, QR codes, and barcodes. Export print-ready PDFs in batch.',f:['Card Templates','Photo Upload','QR / Barcode','Batch Generation','Print-ready PDF'],g:['#4338ca','#3730a3']},
    {n:'Academic Calendar',i:'AC',d:'School-wide academic calendar with events, holidays, exam schedules, and PTMs. Share with parents and staff; integrates with timetable and attendance modules.',f:['Event Scheduling','Holiday Management','Exam Dates','Parent Sharing','Module Integration'],g:['#0369a1','#075985']},
    {n:'School News & Blog',i:'NB',d:'Publish school news, announcements, and blog posts visible to students, parents, and the public. Rich-text editor with image galleries and category tags.',f:['News Publishing','Rich Text Editor','Image Galleries','Category Tags','Public / Private Posts'],g:['#be185d','#9d174d']},
    {n:'Survey & Feedback',i:'SF',d:'Create and distribute surveys to students, parents, and staff — multiple question types, anonymous responses, real-time result dashboards, and CSV export.',f:['Survey Builder','Multiple Question Types','Anonymous Mode','Real-time Results','Export CSV'],g:['#0891b2','#0e7490']},
    {n:'Multi-Branch Management',i:'MB',d:'Centralize management of multiple school branches — shared staff, consolidated reports, branch-level permissions, and cross-branch student transfer workflows.',f:['Branch Dashboard','Shared Staff Pool','Consolidated Reports','Cross-branch Transfers','Branch Permissions'],g:['#1e40af','#1e3a8a']},
    {n:'Online Fee Payment',i:'FP',d:'Integrate payment gateways for online fee collection — Razorpay, Stripe, and UPI support. Automated receipts, payment reminders, and reconciliation reports.',f:['Payment Gateway','Razorpay / Stripe / UPI','Auto Receipts','Payment Reminders','Reconciliation'],g:['#047857','#065f46']},
    {n:'Biometric Integration',i:'BI',d:'Connect biometric attendance devices — fingerprint and face recognition. Auto-sync attendance records, generate muster rolls, and alert for unauthorized access.',f:['Fingerprint Sync','Face Recognition','Auto Attendance','Muster Roll','Access Alerts'],g:['#6d28d9','#5b21b6']},
    {n:'WhatsApp Notifications',i:'WA',d:'Send automated WhatsApp messages to parents and staff — fee reminders, attendance alerts, exam results, and custom broadcasts via WhatsApp Business API.',f:['Fee Reminders','Attendance Alerts','Result Notifications','Custom Broadcasts','Delivery Status'],g:['#15803d','#166534']},
    {n:'QR Code Attendance',i:'QR',d:'Generate QR codes for students and staff. Scan-based attendance marking via mobile or dedicated scanner — real-time updates and daily absentee reports.',f:['QR Code Generation','Mobile Scan','Real-time Updates','Daily Reports','Absentee Alerts'],g:['#0284c7','#0369a1']},
    {n:'Digital Notice Board',i:'DN',d:'Manage school notice boards digitally — publish notices by class, department, or school-wide. Display on TV screens, portal, and mobile app simultaneously.',f:['Notice Publishing','Class / Dept Targeting','TV Screen Display','Portal & App Sync','Notice Archive'],g:['#ea580c','#c2410c']},
    {n:'Tuck Shop / POS',i:'TP',d:'Point-of-sale system for the school tuck shop — product catalog, barcode scanning, cashless student accounts, daily sales reporting, and inventory tracking.',f:['POS Interface','Barcode Scanning','Student Accounts','Cashless Payments','Sales Reports'],g:['#b45309','#92400e']},
    {n:'Learning Analytics',i:'LA',d:'Deep analytics on student learning outcomes — topic-level weakness maps, time-on-task tracking, engagement scoring, and teacher effectiveness metrics.',f:['Learning Heatmaps','Weakness Detection','Engagement Scoring','Teacher Metrics','Trend Analysis'],g:['#db2777','#be185d']},
    {n:'Parent Mobile API',i:'PA',d:'REST API layer for a dedicated parent mobile app — push notifications, attendance, marks, fee dues, timetable, and two-way teacher messaging.',f:['REST API','Push Notifications','Real-time Data','Secure Auth','Webhook Events'],g:['#4f46e5','#4338ca']},
    {n:'Student Mobile API',i:'SA',d:'REST API for the student mobile app — timetable, homework, results, digital ID card, library access, and attendance history on the go.',f:['REST API','Digital ID Card','Homework Access','Result Tracking','Library Integration'],g:['#1d4ed8','#1e40af']},
    {n:'Staff Mobile API',i:'ST',d:'REST API for the staff mobile app — mark attendance, submit leave, view payslips, access timetable, and send class announcements from mobile.',f:['REST API','Mobile Attendance','Leave Application','Payslip Access','Class Announcements'],g:['#0f766e','#115e59']},
    {n:'Google Classroom Sync',i:'GC',d:'Two-way sync with Google Classroom — import class rosters, sync assignments, push marks back to Google Sheets, and link Google Meet for online classes.',f:['Roster Sync','Assignment Import','Marks Push','Google Meet Link','Drive Integration'],g:['#1a73e8','#1557b0']},
    {n:'Zoom Integration',i:'ZM',d:'Create and schedule Zoom meetings directly from the timetable — automatic meeting links for live classes, PTMs, and webinars with attendance tracking.',f:['Meeting Scheduling','Auto Meeting Links','Live Class Support','PTM Meetings','Attendance Tracking'],g:['#2d8cff','#1a6ccd']},
    {n:'Advanced Library Suite',i:'LS',d:'Enhanced library features — OPAC catalog, self-checkout kiosks, inter-library loans, e-book integration, and reading analytics per student.',f:['OPAC Catalog','Self-checkout','E-book Integration','Reading Analytics','Inter-library Loans'],g:['#7c3aed','#6d28d9']},
    {n:'Alumni Network',i:'AN',d:'Extended alumni management — alumni directory, donation campaigns, mentorship matching, job board, and reunion event management for long-term engagement.',f:['Alumni Directory','Donation Campaigns','Mentorship Matching','Job Board','Reunion Events'],g:['#be185d','#9d174d']},
    {n:'Custom Report Builder',i:'CR',d:'Visual drag-and-drop report builder — choose any fields from any module, apply filters, group data, create charts, and schedule automated delivery.',f:['Visual Builder','Cross-module Data','Chart Creation','Filter & Group','Scheduled Delivery'],g:['#312e81','#1e1b4b']},
    {n:'Fee Reminder Automation',i:'FR',d:'Automated fee reminder campaigns — schedule SMS, email, and WhatsApp reminders with escalating urgency, payment links, and auto-reconcile on payment.',f:['Reminder Campaigns','Multi-channel','Payment Links','Escalation Rules','Auto-reconcile'],g:['#b45309','#a16207']},
    {n:'Exam Enhancement Suite',i:'EX',d:'Advanced examination features — online exam creation with MCQs, auto-grading, result analytics, rank lists, mark sheet generation, and parent result SMS.',f:['Online Exams','MCQ Auto-grading','Result Analytics','Rank Lists','Mark Sheet Generation'],g:['#b91c1c','#991b1b']},
    {n:'Timetable AI Optimizer',i:'TO',d:'AI-powered timetable generation — resolves teacher conflicts, respects room capacity, balances subject load, and regenerates on any constraint change.',f:['AI Generation','Conflict Detection','Room Management','Load Balancing','One-click Regenerate'],g:['#7e22ce','#6b21a8']},
    {n:'Staff Appraisal System',i:'AP',d:'Annual and mid-year staff performance appraisal — self-assessment forms, manager ratings, KPI tracking, and appraisal report generation for HR.',f:['Self-assessment','Manager Rating','KPI Tracking','Appraisal Reports','HR Dashboard'],g:['#0e7490','#155e75']},
];

let csFiltered = [...csData];
let csPage = 1;

const LOCK_SVG = '<svg style="width:.45rem;height:.45rem;color:#fff;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>';
const CLOCK_SVG = '<svg style="width:.55rem;height:.55rem;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>';

function csCardHtml(b) {
    const tags = b.f.slice(0,4).map(f =>
        `<span class="bs-feature-tag" style="background:#ede9fe;color:#7c3aed;opacity:.85;">${f}</span>`
    ).join('') + (b.f.length > 4 ? `<span class="bs-feature-tag" style="background:#f1f5f9;color:#94a3b8;">+${b.f.length-4} more</span>` : '');
    return `<div class="bs-card bs-card-coming">
      <div class="bs-card-top">
        <div style="position:relative;flex-shrink:0;">
          <div class="bs-icon bs-icon-coming" style="background:linear-gradient(135deg,${b.g[0]},${b.g[1]});opacity:.7;">${b.i}</div>
          <div class="bs-coming-lock">${LOCK_SVG}</div>
        </div>
        <div class="bs-card-meta">
          <div class="bs-card-name" style="color:#374151;">${b.n}</div>
          <div class="bs-card-author">Vidra Pro</div>
          <div style="margin-top:.25rem;"><span class="bs-coming-badge">${CLOCK_SVG}Coming Soon</span></div>
        </div>
      </div>
      <div class="bs-card-body">
        <div class="bs-card-desc" style="color:#6b7280;">${b.d}</div>
        <div class="bs-features">${tags}</div>
      </div>
      <div class="bs-card-footer" style="background:rgba(238,242,255,.4);">
        <span class="bs-version-badge" style="background:#ede9fe;color:#7c3aed;">v1.0</span>
        <button class="bs-btn-soon" disabled>${LOCK_SVG} Coming Soon</button>
      </div>
    </div>`;
}

function csRender() {
    const start  = (csPage - 1) * CS_PER_PAGE;
    const page   = csFiltered.slice(start, start + CS_PER_PAGE);
    const grid   = document.getElementById('cs-grid');
    const empty  = document.getElementById('cs-empty');
    const count  = document.getElementById('cs-count');

    if (csFiltered.length === 0) {
        grid.style.display = 'none';
        grid.innerHTML = '';
        empty.style.display = '';
        count.textContent = '0 results';
    } else {
        grid.style.display = '';
        grid.innerHTML = page.map(csCardHtml).join('');
        empty.style.display = 'none';
        const end = Math.min(start + CS_PER_PAGE, csFiltered.length);
        count.textContent = `${start + 1}–${end} of ${csFiltered.length}`;
    }
    csPaginate();
}

function csPaginate() {
    const total = Math.ceil(csFiltered.length / CS_PER_PAGE);
    const pg    = document.getElementById('cs-pagination');
    if (total <= 1) { pg.innerHTML = ''; return; }

    let pages = [];
    if (total <= 7) {
        for (let i = 1; i <= total; i++) pages.push(i);
    } else if (csPage <= 4) {
        pages = [1, 2, 3, 4, 5, '…', total];
    } else if (csPage >= total - 3) {
        pages = [1, '…', total-4, total-3, total-2, total-1, total];
    } else {
        pages = [1, '…', csPage-1, csPage, csPage+1, '…', total];
    }

    let html = `<button class="cs-pg-btn" onclick="csGo(${csPage-1})" ${csPage===1?'disabled':''} style="${csPage===1?'opacity:.35':''}">‹ Prev</button>`;
    pages.forEach(p => {
        if (p === '…') {
            html += `<span style="padding:.35rem .25rem;color:#94a3b8;font-size:.8125rem;">…</span>`;
        } else {
            html += `<button class="cs-pg-btn${p===csPage?' active':''}" onclick="csGo(${p})">${p}</button>`;
        }
    });
    html += `<button class="cs-pg-btn" onclick="csGo(${csPage+1})" ${csPage===total?'disabled':''} style="${csPage===total?'opacity:.35':''}">Next ›</button>`;
    pg.innerHTML = html;
}

function csGo(page) {
    const total = Math.ceil(csFiltered.length / CS_PER_PAGE);
    if (page < 1 || page > total) return;
    csPage = page;
    csRender();
    document.getElementById('cs-grid').scrollIntoView({behavior:'smooth', block:'nearest'});
}

document.getElementById('cs-search').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    csFiltered = q
        ? csData.filter(b =>
            b.n.toLowerCase().includes(q) ||
            b.d.toLowerCase().includes(q) ||
            b.f.some(f => f.toLowerCase().includes(q))
          )
        : [...csData];
    csPage = 1;
    csRender();
});

document.getElementById('cs-total-badge').textContent = csData.length;
csRender();
</script>

@php
function bundleColor(string $name): array {
    $palettes = [
        ['#4f46e5','#7c3aed'],
        ['#0891b2','#0e7490'],
        ['#059669','#047857'],
        ['#d97706','#b45309'],
        ['#dc2626','#b91c1c'],
        ['#7c3aed','#6d28d9'],
        ['#0284c7','#0369a1'],
        ['#be185d','#9d174d'],
    ];
    $idx = abs(crc32($name)) % count($palettes);
    return $palettes[$idx];
}
@endphp

@endsection
