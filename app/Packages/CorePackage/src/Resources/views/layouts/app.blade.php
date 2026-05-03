<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School Management'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        /* ── Sidebar links ── */
        .sidebar-link { display:flex; align-items:center; gap:.75rem; padding:.5rem .75rem; border-radius:.5rem; font-size:.875rem; font-weight:500; transition:background .15s,color .15s; color:#475569; text-decoration:none; }
        .sidebar-link:hover { background:#f8fafc; color:#1e293b; }
        .sidebar-link.active { background:#eef2ff; color:#4338ca; }
        .sidebar-link .icon { width:1.125rem; height:1.125rem; flex-shrink:0; }

        /* ── Sidebar scrollbar ── */
        #sidebar-nav::-webkit-scrollbar { width:4px; }
        #sidebar-nav::-webkit-scrollbar-track { background:transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:3px; }
        #sidebar-nav::-webkit-scrollbar-thumb:hover { background:#94a3b8; }

        /* ── Section toggles ── */
        .section-toggle {
            display:flex; align-items:center; justify-content:space-between;
            width:100%; background:none; border:none; cursor:pointer;
            padding:.25rem .75rem; margin:.625rem 0 .2rem;
            border-radius:.375rem; text-align:left;
        }
        .section-toggle:hover .section-label { color:#64748b; }
        .section-label { font-size:.65rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; }
        .section-chevron { width:.625rem; height:.625rem; color:#cbd5e1; transition:transform .2s; flex-shrink:0; }

        /* ── Section items container ── */
        .section-items { display:flex; flex-direction:column; gap:.125rem; }

        /* ── Full sidebar transitions ── */
        #sidebar {
            position:fixed; inset-y:0; left:0; width:16rem; height:100vh;
            background:#fff; border-right:1px solid #e2e8f0;
            display:flex; flex-direction:column; z-index:30; overflow:hidden;
            transition:transform .25s ease;
        }
        #sidebar.sidebar-hidden { transform:translateX(-100%); }

        #main-content { flex:1; display:flex; flex-direction:column; margin-left:16rem; min-width:0; transition:margin-left .25s ease; }
        #main-content.no-sidebar { margin-left:0; }

        /* ── Mobile overlay ── */
        #sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.25); z-index:29; }
        #sidebar-overlay.active { display:block; }
    </style>
</head>
<body style="background:#f1f5f9; margin:0;">

<div style="display:flex; min-height:100vh;">

    {{-- Mobile backdrop --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- ─── Sidebar ─── --}}
    <aside id="sidebar">

        {{-- Logo + close button --}}
        <div style="height:4rem;display:flex;align-items:center;gap:.75rem;padding:0 .625rem 0 1.25rem;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
            <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 1px 3px rgba(79,70,229,.3);">
                <svg style="width:1.125rem;height:1.125rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div style="min-width:0;flex:1;">
                <p style="font-size:.875rem;font-weight:700;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ config('app.name', 'School Management') }}</p>
                <p style="font-size:.65rem;color:#94a3b8;margin:0;">Management System</p>
            </div>
            <button onclick="toggleSidebar()" title="Collapse sidebar"
                style="width:1.75rem;height:1.75rem;background:none;border:1px solid #e2e8f0;border-radius:.375rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#94a3b8;padding:0;">
                <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        {{-- Nav (scrollable) --}}
        <nav id="sidebar-nav" style="flex:1;min-height:0;overflow-y:auto;padding:.5rem .75rem;display:flex;flex-direction:column;">
            @php
                $menuService = app(\App\Services\MenuService::class);
            @endphp

            {{-- ── Dynamic Menu Sections ── --}}
            @foreach($menuService->getSections() as $sectionKey => $section)
                @if($sectionKey !== 'insights' && $sectionKey !== 'system' && count($section->getItems()) > 0)
                    <button class="section-toggle" onclick="toggleSection('{{ $section->id }}')">
                        <span class="section-label">{{ $section->label }}</span>
                        <svg id="{{ $section->id }}-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="{{ $section->id }}" class="section-items">
                        @foreach($section->getItems() as $item)
                            <a href="{{ route($item->route) }}" class="sidebar-link {{ request()->routeIs($item->activePattern) ? 'active' : '' }}">
                                {!! $item->icon !!}
                                {{ $item->label }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endforeach


            {{-- ── System (Dynamic from MenuService) ── --}}
            @php
                $menuService = app(\App\Services\MenuService::class);
                $systemSection = $menuService->getSection('system');
            @endphp
            @if($systemSection && count($systemSection->getItems()) > 0)
                <button class="section-toggle" onclick="toggleSection('s-system')">
                    <span class="section-label">{{ $systemSection->label }}</span>
                    <svg id="s-system-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="s-system" class="section-items">
                    @foreach($systemSection->getItems() as $item)
                        <a href="{{ route($item->route) }}" class="sidebar-link {{ request()->routeIs($item->activePattern) ? 'active' : '' }}">
                            {!! $item->icon !!}
                            {{ $item->label }}
                        </a>
                    @endforeach
                </div>
            @endif

        </nav>

        {{-- User area --}}
        <div style="padding:.75rem 1rem;border-top:1px solid #f1f5f9;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:.625rem;">
                <div style="width:2rem;height:2rem;background:#e2e8f0;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:1rem;height:1rem;color:#64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div style="min-width:0;flex:1;">
                    <p style="font-size:.75rem;font-weight:600;color:#334155;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()?->name ?? 'Administrator' }}</p>
                    <p style="font-size:.65rem;color:#94a3b8;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()?->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="flex-shrink:0;">
                    @csrf
                    <button type="submit" title="Sign out"
                        style="width:1.75rem;height:1.75rem;background:none;border:1px solid #e2e8f0;border-radius:.375rem;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#94a3b8;padding:0;transition:color .15s,border-color .15s;"
                        onmouseover="this.style.color='#ef4444';this.style.borderColor='#fca5a5'"
                        onmouseout="this.style.color='#94a3b8';this.style.borderColor='#e2e8f0'">
                        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ─── Main ─── --}}
    <div id="main-content">

        {{-- Top bar --}}
        <header style="position:sticky;top:0;z-index:20;height:4rem;background:#fff;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;padding:0 1.5rem;gap:1rem;">

            {{-- Sidebar toggle button --}}
            <button id="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar"
                style="width:2rem;height:2rem;background:none;border:1px solid #e2e8f0;border-radius:.375rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#64748b;padding:0;">
                <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div style="flex:1;min-width:0;">
                @yield('breadcrumb')
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <a href="/bundle-installer" style="display:inline-flex;align-items:center;gap:.375rem;padding:.375rem .875rem;background:#4f46e5;color:#fff;border-radius:9999px;font-size:.75rem;font-weight:600;text-decoration:none;transition:background .2s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                    <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Install Bundle
                </a>
                <span style="font-size:.75rem;color:#94a3b8;">{{ now()->format('D, M d Y') }}</span>
            </div>
        </header>

        {{-- Toast notifications --}}
        <div style="position:fixed;top:4.5rem;right:1rem;z-index:50;display:flex;flex-direction:column;gap:.5rem;width:20rem;" id="flash-container">
            @if(session('success'))
            <div class="flash-msg" style="background:#fff;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:.625rem;padding:.875rem 1rem;box-shadow:0 4px 6px -1px rgba(0,0,0,.1);display:flex;align-items:flex-start;gap:.75rem;">
                <div style="width:1.25rem;height:1.25rem;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:.75rem;height:.75rem;color:#16a34a;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:.8125rem;font-weight:600;color:#166534;margin:0;">Success</p>
                    <p style="font-size:.75rem;color:#15803d;margin:.125rem 0 0;line-height:1.4;">{{ session('success') }}</p>
                </div>
                <button onclick="this.closest('.flash-msg').remove()" style="background:none;border:none;cursor:pointer;color:#86efac;padding:0;line-height:1;margin-top:-.125rem;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="flash-msg" style="background:#fff;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:.625rem;padding:.875rem 1rem;box-shadow:0 4px 6px -1px rgba(0,0,0,.1);display:flex;align-items:flex-start;gap:.75rem;">
                <div style="width:1.25rem;height:1.25rem;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:.75rem;height:.75rem;color:#dc2626;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:.8125rem;font-weight:600;color:#991b1b;margin:0;">Error</p>
                    <p style="font-size:.75rem;color:#b91c1c;margin:.125rem 0 0;line-height:1.4;">{{ session('error') }}</p>
                </div>
                <button onclick="this.closest('.flash-msg').remove()" style="background:none;border:none;cursor:pointer;color:#fca5a5;padding:0;line-height:1;margin-top:-.125rem;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif
        </div>

        {{-- Content --}}
        <main style="flex:1;padding:1.5rem;">
            @yield('content')
        </main>

        <footer style="border-top:1px solid #e2e8f0;padding:.875rem 1.5rem;background:#fff;">
            <p style="text-align:center;font-size:.7rem;color:#94a3b8;margin:0;">&copy; {{ date('Y') }} {{ config('app.name', 'School Management') }}. All rights reserved.</p>
        </footer>
    </div>
</div>

<script>
const SB_SECTIONS = ['s-people', 's-academics', 's-subjects', 's-attendance', 's-finance', 's-timetable', 's-communication', 's-hostel', 's-exams', 's-datatransfer', 's-rbac', 's-system'];

function toggleSection(id) {
    const content = document.getElementById(id);
    const chevron = document.getElementById(id + '-ch');
    if (!content) return;
    const isOpen = content.style.display !== 'none';
    content.style.display = isOpen ? 'none' : 'flex';
    if (chevron) chevron.style.transform = isOpen ? 'rotate(-90deg)' : '';
    try { localStorage.setItem('sb-' + id, isOpen ? 'closed' : 'open'); } catch(e) {}
}

function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const main     = document.getElementById('main-content');
    const overlay  = document.getElementById('sidebar-overlay');
    const isOpen   = !sidebar.classList.contains('sidebar-hidden');
    sidebar.classList.toggle('sidebar-hidden', isOpen);
    main.classList.toggle('no-sidebar', isOpen);
    if (overlay) overlay.classList.toggle('active', !isOpen);
    try { localStorage.setItem('sb-sidebar', isOpen ? 'closed' : 'open'); } catch(e) {}
}

document.addEventListener('DOMContentLoaded', function () {

    // Restore section collapsed states
    SB_SECTIONS.forEach(function (id) {
        try {
            if (localStorage.getItem('sb-' + id) === 'closed') {
                const content = document.getElementById(id);
                const chevron = document.getElementById(id + '-ch');
                if (content) content.style.display = 'none';
                if (chevron) chevron.style.transform = 'rotate(-90deg)';
            }
        } catch(e) {}
    });

    // Always expand the section containing the active link (overrides saved state)
    const activeLink = document.querySelector('.sidebar-link.active');
    if (activeLink) {
        const section = activeLink.closest('.section-items');
        if (section && section.id) {
            section.style.display = 'flex';
            const chevron = document.getElementById(section.id + '-ch');
            if (chevron) chevron.style.transform = '';
            try { localStorage.setItem('sb-' + section.id, 'open'); } catch(e) {}
        }
    }

    // Restore full sidebar state
    try {
        if (localStorage.getItem('sb-sidebar') === 'closed') {
            document.getElementById('sidebar').classList.add('sidebar-hidden');
            document.getElementById('main-content').classList.add('no-sidebar');
        }
    } catch(e) {}

    // Flash auto-dismiss
    document.querySelectorAll('.flash-msg').forEach(function (msg) {
        setTimeout(function () {
            msg.style.transition = 'opacity .4s, transform .4s';
            msg.style.opacity = '0';
            msg.style.transform = 'translateX(.75rem)';
            setTimeout(function () { msg.remove(); }, 400);
        }, 5000);
    });
});
</script>
</body>
</html>
