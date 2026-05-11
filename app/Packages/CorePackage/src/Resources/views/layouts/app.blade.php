<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School Management'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    /* ══════════════════════════════════════════════════════
       CSS CUSTOM PROPERTIES — Light & Dark
    ══════════════════════════════════════════════════════ */
    :root {
        --bg-app:        #f1f5f9;
        --bg-surface:    #ffffff;
        --bg-surface-2:  #f8fafc;
        --bg-surface-3:  #f1f5f9;
        --border:        #e2e8f0;
        --border-light:  #f1f5f9;
        --text-primary:  #1e293b;
        --text-secondary:#475569;
        --text-muted:    #94a3b8;
        --text-faint:    #cbd5e1;
        --accent:        #4f46e5;
        --accent-hover:  #4338ca;
        --accent-soft:   #eef2ff;
        --accent-text:   #4338ca;
        --shadow-sm:     0 1px 3px rgba(0,0,0,.08);
        --shadow-md:     0 4px 12px rgba(0,0,0,.08);
        --overlay-bg:    rgba(0,0,0,.35);
        /* loader */
        --ldr-bg:        #ffffff;
        --ldr-name-clr:  #1e293b;
        --ldr-badge-bg:  #eef2ff;
        --ldr-badge-bd:  #c7d2fe;
        --ldr-badge-clr: #4338ca;
        --ldr-icon-bg:   #ffffff;
        --ldr-icon-bd:   #e2e8f0;
        --ldr-bar-bg:    #e2e8f0;
        /* scrollbar */
        --scrollbar-thumb: #cbd5e1;
        /* badge colors */
        --badge-green-bg:  #f0fdf4; --badge-green-clr:  #166534; --badge-green-bd:  rgba(22,101,52,.15);
        --badge-yellow-bg: #fefce8; --badge-yellow-clr: #854d0e; --badge-yellow-bd: rgba(133,77,14,.15);
        --badge-blue-bg:   #eff6ff; --badge-blue-clr:   #1e40af; --badge-blue-bd:   rgba(30,64,175,.15);
        --badge-red-bg:    #fef2f2; --badge-red-clr:    #991b1b; --badge-red-bd:    rgba(153,27,27,.15);
        --badge-indigo-bg: #eef2ff; --badge-indigo-clr: #3730a3; --badge-indigo-bd: rgba(55,48,163,.15);
        --badge-purple-bg: #faf5ff; --badge-purple-clr: #6b21a8; --badge-purple-bd: rgba(107,33,168,.15);
        --badge-orange-bg: #fff7ed; --badge-orange-clr: #9a3412; --badge-orange-bd: rgba(154,52,18,.15);
        --badge-gray-bg:   #f8fafc; --badge-gray-clr:   #475569; --badge-gray-bd:   rgba(71,85,105,.15);
    }

    [data-theme="dark"] {
        --bg-app:        #0f172a;
        --bg-surface:    #1e293b;
        --bg-surface-2:  #1a2744;
        --bg-surface-3:  #162032;
        --border:        #334155;
        --border-light:  #1e293b;
        --text-primary:  #f1f5f9;
        --text-secondary:#94a3b8;
        --text-muted:    #64748b;
        --text-faint:    #334155;
        --accent:        #6366f1;
        --accent-hover:  #4f46e5;
        --accent-soft:   rgba(99,102,241,.15);
        --accent-text:   #818cf8;
        --shadow-sm:     0 1px 3px rgba(0,0,0,.3);
        --shadow-md:     0 4px 12px rgba(0,0,0,.3);
        --overlay-bg:    rgba(0,0,0,.6);
        /* loader */
        --ldr-bg:        #0f172a;
        --ldr-name-clr:  #f1f5f9;
        --ldr-badge-bg:  rgba(99,102,241,.15);
        --ldr-badge-bd:  rgba(99,102,241,.3);
        --ldr-badge-clr: #818cf8;
        --ldr-icon-bg:   #1e293b;
        --ldr-icon-bd:   #334155;
        --ldr-bar-bg:    #334155;
        --scrollbar-thumb: #334155;
        /* badge colors — dark */
        --badge-green-bg:  rgba(22,163,74,.15);  --badge-green-clr:  #4ade80; --badge-green-bd:  rgba(22,163,74,.3);
        --badge-yellow-bg: rgba(234,179,8,.12);  --badge-yellow-clr: #fde047; --badge-yellow-bd: rgba(234,179,8,.3);
        --badge-blue-bg:   rgba(37,99,235,.15);  --badge-blue-clr:   #60a5fa; --badge-blue-bd:   rgba(37,99,235,.3);
        --badge-red-bg:    rgba(220,38,38,.15);  --badge-red-clr:    #f87171; --badge-red-bd:    rgba(220,38,38,.3);
        --badge-indigo-bg: rgba(99,102,241,.15); --badge-indigo-clr: #818cf8; --badge-indigo-bd: rgba(99,102,241,.3);
        --badge-purple-bg: rgba(168,85,247,.12); --badge-purple-clr: #c084fc; --badge-purple-bd: rgba(168,85,247,.3);
        --badge-orange-bg: rgba(234,88,12,.12);  --badge-orange-clr: #fb923c; --badge-orange-bd: rgba(234,88,12,.3);
        --badge-gray-bg:   rgba(71,85,105,.2);   --badge-gray-clr:   #94a3b8; --badge-gray-bd:   rgba(71,85,105,.35);
    }

    /* ── Base ── */
    *, *::before, *::after { box-sizing: border-box; }
    body {
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        background: var(--bg-app);
        margin: 0;
        color: var(--text-primary);
        transition: background .25s, color .25s;
    }

    /* ── Sidebar ── */
    #sidebar {
        position: fixed; inset-y: 0; left: 0;
        width: 16rem; height: 100vh;
        background: var(--bg-surface);
        border-right: 1px solid var(--border);
        display: flex; flex-direction: column;
        z-index: 40; overflow: hidden;
        transition: transform .25s ease, background .25s, border-color .25s;
    }
    /* Hidden state — JS-only, no CSS media query override */
    #sidebar.sidebar-hidden { transform: translateX(-100%); }

    #main-content {
        flex: 1; display: flex; flex-direction: column;
        margin-left: 16rem; min-width: 0;
        transition: margin-left .25s ease;
    }
    #main-content.no-sidebar { margin-left: 0; }

    /* Mobile: content never shifts — overlay used instead */
    @media (max-width: 767px) {
        #main-content,
        #main-content.no-sidebar { margin-left: 0 !important; }
    }

    /* ── Mobile overlay ── */
    #sidebar-overlay {
        display: none; position: fixed; inset: 0;
        background: var(--overlay-bg); z-index: 39;
        backdrop-filter: blur(2px);
    }
    #sidebar-overlay.active { display: block; }

    /* ── Sidebar logo area ── */
    .sb-logo-row {
        height: 4rem; display: flex; align-items: center;
        gap: .75rem; padding: 0 .625rem 0 1.25rem;
        border-bottom: 1px solid var(--border-light);
        flex-shrink: 0;
    }
    .sb-logo-icon {
        width: 2.25rem; height: 2.25rem;
        background: linear-gradient(135deg, var(--accent), #6366f1);
        border-radius: .625rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(79,70,229,.3);
    }
    .sb-logo-name {
        font-size: .875rem; font-weight: 700;
        color: var(--text-primary); margin: 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sb-logo-sub { font-size: .65rem; color: var(--text-muted); margin: 0; }
    .sb-collapse-btn {
        width: 1.75rem; height: 1.75rem;
        background: none; border: 1px solid var(--border);
        border-radius: .375rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: var(--text-muted); padding: 0;
        transition: color .15s, border-color .15s, background .15s;
    }
    .sb-collapse-btn:hover { color: var(--text-secondary); background: var(--bg-surface-2); }

    /* ── Sidebar nav ── */
    #sidebar-nav { flex: 1; min-height: 0; overflow-y: auto; padding: .5rem .75rem; display: flex; flex-direction: column; }
    #sidebar-nav::-webkit-scrollbar { width: 4px; }
    #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    #sidebar-nav::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 3px; }

    .sidebar-link {
        display: flex; align-items: center; gap: .75rem;
        padding: .5rem .75rem; border-radius: .5rem;
        font-size: .875rem; font-weight: 500;
        transition: background .15s, color .15s;
        color: var(--text-secondary); text-decoration: none;
    }
    .sidebar-link:hover { background: var(--bg-surface-2); color: var(--text-primary); }
    .sidebar-link.active { background: var(--accent-soft); color: var(--accent-text); }
    .sidebar-link .icon { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }

    /* ── Section toggles ── */
    .section-toggle {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; background: none; border: none; cursor: pointer;
        padding: .25rem .75rem; margin: .625rem 0 .2rem;
        border-radius: .375rem; text-align: left;
    }
    .section-toggle:hover .section-label { color: var(--text-secondary); }
    .section-label {
        font-size: .65rem; font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .06em;
    }
    .section-chevron {
        width: .625rem; height: .625rem;
        color: var(--text-faint); transition: transform .2s; flex-shrink: 0;
    }
    .section-items { display: flex; flex-direction: column; gap: .125rem; }

    /* ── Sidebar user area ── */
    .sb-user-row {
        padding: .75rem 1rem; border-top: 1px solid var(--border-light); flex-shrink: 0;
        display: flex; align-items: center; gap: .625rem;
    }
    .sb-avatar {
        width: 2rem; height: 2rem; background: var(--bg-surface-3);
        border: 1px solid var(--border);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: var(--text-secondary);
    }
    .sb-user-name {
        font-size: .75rem; font-weight: 600; color: var(--text-primary);
        margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sb-user-email {
        font-size: .65rem; color: var(--text-muted);
        margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sb-logout-btn {
        width: 1.75rem; height: 1.75rem; background: none;
        border: 1px solid var(--border); border-radius: .375rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); padding: 0;
        transition: color .15s, border-color .15s;
        flex-shrink: 0;
    }
    .sb-logout-btn:hover { color: #ef4444; border-color: #fca5a5; }

    /* ── Top bar ── */
    #topbar {
        position: sticky; top: 0; z-index: 20;
        height: 4rem; background: var(--bg-surface);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center;
        padding: 0 1.25rem; gap: .75rem;
        transition: background .25s, border-color .25s;
    }
    .topbar-hamburger {
        width: 2.25rem; height: 2.25rem; background: none;
        border: 1px solid var(--border); border-radius: .5rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: var(--text-secondary); padding: 0;
        transition: background .15s, color .15s;
    }
    .topbar-hamburger:hover { background: var(--bg-surface-2); color: var(--text-primary); }

    /* ── Theme toggle ── */
    .theme-toggle {
        width: 2.25rem; height: 2.25rem; background: none;
        border: 1px solid var(--border); border-radius: .5rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: var(--text-secondary); padding: 0;
        transition: background .15s, color .15s;
    }
    .theme-toggle:hover { background: var(--bg-surface-2); color: var(--text-primary); }

    /* ── Install bundle btn ── */
    .install-btn {
        display: inline-flex; align-items: center; gap: .375rem;
        padding: .375rem .875rem;
        background: var(--accent); color: #fff;
        border-radius: 9999px; font-size: .75rem; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer;
        transition: background .2s;
        flex-shrink: 0;
    }
    .install-btn:hover { background: var(--accent-hover); }
    @media (max-width: 480px) {
        .install-btn span, #topbar-date { display: none; }
    }

    /* ── Main content area ── */
    #page-main {
        flex: 1; padding: 1.5rem;
        background: var(--bg-app);
        transition: background .25s;
    }

    /* ── Footer ── */
    #page-footer {
        border-top: 1px solid var(--border);
        padding: .875rem 1.5rem;
        background: var(--bg-surface);
        transition: background .25s, border-color .25s;
    }
    #page-footer p { text-align: center; font-size: .7rem; color: var(--text-muted); margin: 0; }

    /* ── Flash toasts ── */
    .flash-msg {
        background: var(--bg-surface);
        border-radius: .625rem;
        padding: .875rem 1rem;
        box-shadow: var(--shadow-md);
        display: flex; align-items: flex-start; gap: .75rem;
    }
    .flash-msg.flash-success { border: 1px solid #bbf7d0; border-left: 4px solid #16a34a; }
    .flash-msg.flash-error   { border: 1px solid #fecaca; border-left: 4px solid #dc2626; }
    .flash-title-success { font-size: .8125rem; font-weight: 600; color: #166534; margin: 0; }
    .flash-body-success  { font-size: .75rem; color: #15803d; margin: .125rem 0 0; line-height: 1.4; }
    .flash-title-error   { font-size: .8125rem; font-weight: 600; color: #991b1b; margin: 0; }
    .flash-body-error    { font-size: .75rem; color: #b91c1c; margin: .125rem 0 0; line-height: 1.4; }
    [data-theme="dark"] .flash-msg.flash-success { border-color: rgba(34,197,94,.25); border-left-color: #22c55e; }
    [data-theme="dark"] .flash-msg.flash-error   { border-color: rgba(239,68,68,.25);  border-left-color: #ef4444; }
    [data-theme="dark"] .flash-title-success { color: #86efac; }
    [data-theme="dark"] .flash-body-success  { color: #6ee7b7; }
    [data-theme="dark"] .flash-title-error   { color: #fca5a5; }
    [data-theme="dark"] .flash-body-error    { color: #f87171; }

    /* ══════════════════════════════════════════════════════
       PAGE LOADER
    ══════════════════════════════════════════════════════ */
    #page-loader {
        position: fixed; inset: 0; z-index: 9999;
        background: var(--ldr-bg);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        transition: opacity .5s ease, transform .5s ease;
    }
    #page-loader.out { opacity: 0; transform: scale(1.05); pointer-events: none; }
    .ldr-rings { position: absolute; width: 160px; height: 160px; pointer-events: none; }
    .ldr-ring {
        position: absolute; inset: 0; border-radius: 50%;
        border: 1px solid rgba(79,70,229,.22);
        animation: ldr-pulse 2.2s ease-out infinite;
    }
    .ldr-ring:nth-child(2) { animation-delay: .75s; }
    .ldr-ring:nth-child(3) { animation-delay: 1.5s; }
    @keyframes ldr-pulse { 0% { transform:scale(.5);opacity:.9; } 100% { transform:scale(1.7);opacity:0; } }
    .ldr-arc-wrap { position:relative; width:76px; height:76px; flex-shrink:0; }
    .ldr-arc {
        position:absolute; inset:0; border-radius:50%;
        background: conic-gradient(from 0deg, var(--accent) 0%, #818cf8 35%, transparent 60%);
        animation: ldr-spin 1s linear infinite;
        -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 0);
        mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 0);
    }
    @keyframes ldr-spin { to { transform:rotate(360deg); } }
    .ldr-icon-bg {
        position:absolute; inset:7px; border-radius:50%;
        background: var(--ldr-icon-bg); border:1px solid var(--ldr-icon-bd);
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 2px 8px rgba(79,70,229,.12);
    }
    .ldr-logo {
        width:28px; height:28px; border-radius:7px;
        background:linear-gradient(135deg, var(--accent), #6366f1);
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 3px 10px rgba(79,70,229,.35);
    }
    .ldr-text { margin-top:24px; text-align:center; animation:ldr-fadein .5s ease both; animation-delay:.15s; }
    @keyframes ldr-fadein { from{opacity:0;transform:translateY(6px);} to{opacity:1;transform:translateY(0);} }
    .ldr-name { font-size:1.1rem; font-weight:800; color:var(--ldr-name-clr); letter-spacing:-.025em; margin-bottom:7px; }
    .ldr-badge {
        display:inline-flex; align-items:center; gap:6px;
        padding:4px 11px; background:var(--ldr-badge-bg);
        border:1px solid var(--ldr-badge-bd); border-radius:999px;
        font-size:.68rem; font-weight:700; color:var(--ldr-badge-clr);
        letter-spacing:.055em; text-transform:uppercase;
    }
    .ldr-dot { width:6px;height:6px;border-radius:50%;background:#22c55e;flex-shrink:0;animation:ldr-blink 1s ease-in-out infinite; }
    @keyframes ldr-blink { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.3;transform:scale(.65);} }
    .ldr-dots { display:inline-flex; gap:3px; margin-left:1px; }
    .ldr-dots span { width:3px;height:3px;border-radius:50%;background:var(--accent);animation:ldr-bounce .85s ease-in-out infinite; }
    .ldr-dots span:nth-child(2){animation-delay:.14s;} .ldr-dots span:nth-child(3){animation-delay:.28s;}
    @keyframes ldr-bounce { 0%,80%,100%{transform:translateY(0);opacity:.35;} 40%{transform:translateY(-4px);opacity:1;} }
    .ldr-progress { margin-top:28px; width:140px; height:2px; background:var(--ldr-bar-bg); border-radius:2px; overflow:hidden; }
    .ldr-bar { height:100%; width:0; background:linear-gradient(90deg,var(--accent),#818cf8); border-radius:2px; animation:ldr-fill 1.8s cubic-bezier(.4,0,.2,1) forwards; }
    @keyframes ldr-fill { 0%{width:0;} 55%{width:70%;} 80%{width:88%;} 100%{width:100%;} }
    body.loading { overflow: hidden; }

    /* ══════════════════════════════════════════════════════
       GLOBAL DARK-MODE OVERRIDES
       Inline-style overrides for package views using hardcoded colours.
       Attribute-value selectors + !important beat inline specificity in dark mode.
    ══════════════════════════════════════════════════════ */

    /* ── Surfaces ── */
    [data-theme="dark"] [style*="background:#fff"],
    [data-theme="dark"] [style*="background:#ffffff"],
    [data-theme="dark"] [style*="background: #fff"],
    [data-theme="dark"] [style*="background: #ffffff"] {
        background: var(--bg-surface) !important;
    }
    [data-theme="dark"] [style*="background:#f8fafc"],
    [data-theme="dark"] [style*="background: #f8fafc"] {
        background: var(--bg-surface-2) !important;
    }
    [data-theme="dark"] [style*="background:#f1f5f9"],
    [data-theme="dark"] [style*="background: #f1f5f9"] {
        background: var(--bg-surface-3) !important;
    }

    /* ── Borders ── */
    [data-theme="dark"] [style*="border:1px solid #e2e8f0"],
    [data-theme="dark"] [style*="border: 1px solid #e2e8f0"] {
        border-color: var(--border) !important;
    }
    [data-theme="dark"] [style*="border:1px solid #f1f5f9"],
    [data-theme="dark"] [style*="border: 1px solid #f1f5f9"] {
        border-color: var(--border-light) !important;
    }
    [data-theme="dark"] [style*="border-bottom:1px solid #e2e8f0"],
    [data-theme="dark"] [style*="border-bottom: 1px solid #e2e8f0"],
    [data-theme="dark"] [style*="border-bottom:1px solid #f1f5f9"],
    [data-theme="dark"] [style*="border-bottom: 1px solid #f1f5f9"] {
        border-bottom-color: var(--border) !important;
    }

    /* ── Primary text ── */
    [data-theme="dark"] [style*="color:#1e293b"],
    [data-theme="dark"] [style*="color: #1e293b"],
    [data-theme="dark"] [style*="color:#374151"],
    [data-theme="dark"] [style*="color: #374151"],
    [data-theme="dark"] [style*="color:#111827"],
    [data-theme="dark"] [style*="color: #111827"] {
        color: var(--text-primary) !important;
    }

    /* ── Secondary / muted text ── */
    [data-theme="dark"] [style*="color:#64748b"],
    [data-theme="dark"] [style*="color: #64748b"],
    [data-theme="dark"] [style*="color:#475569"],
    [data-theme="dark"] [style*="color: #475569"],
    [data-theme="dark"] [style*="color:#6b7280"],
    [data-theme="dark"] [style*="color: #6b7280"] {
        color: var(--text-secondary) !important;
    }
    [data-theme="dark"] [style*="color:#94a3b8"],
    [data-theme="dark"] [style*="color: #94a3b8"],
    [data-theme="dark"] [style*="color:#9ca3af"],
    [data-theme="dark"] [style*="color: #9ca3af"] {
        color: var(--text-muted) !important;
    }

    /* ── Table-specific ── */
    [data-theme="dark"] thead tr[style],
    [data-theme="dark"] thead tr {
        background: var(--bg-surface-2) !important;
    }
    [data-theme="dark"] thead th {
        border-bottom-color: var(--border) !important;
        color: var(--text-muted) !important;
    }
    [data-theme="dark"] tbody tr[style*="border-bottom"] {
        border-bottom-color: var(--border) !important;
    }
    [data-theme="dark"] tbody tr:hover {
        background: var(--bg-surface-2) !important;
    }
    [data-theme="dark"] table {
        color: var(--text-primary);
    }

    /* ── Page header text ── */
    [data-theme="dark"] #page-main > div > h1 {
        color: var(--text-primary) !important;
    }
    [data-theme="dark"] #page-main > div > p {
        color: var(--text-secondary) !important;
    }

    /* ── Pagination ── */
    [data-theme="dark"] nav[aria-label="Pagination Navigation"] span,
    [data-theme="dark"] nav[aria-label="Pagination Navigation"] a,
    [data-theme="dark"] nav[aria-label="pagination"] span,
    [data-theme="dark"] nav[aria-label="pagination"] a {
        background: var(--bg-surface) !important;
        border-color: var(--border) !important;
        color: var(--text-primary) !important;
    }
    [data-theme="dark"] nav span[aria-current="page"] > span {
        background: var(--accent) !important;
        border-color: var(--accent) !important;
        color: #fff !important;
    }

    /* ── Form section backgrounds ── */
    [data-theme="dark"] [style*="background:#f9fafb"],
    [data-theme="dark"] [style*="background: #f9fafb"] {
        background: var(--bg-surface-2) !important;
    }
    </style>
</head>
<body class="loading">

<!-- ══ PAGE LOADER ══ -->
<div id="page-loader" role="status" aria-label="Loading">
    <div class="ldr-rings"><div class="ldr-ring"></div><div class="ldr-ring"></div><div class="ldr-ring"></div></div>
    <div class="ldr-arc-wrap">
        <div class="ldr-arc"></div>
        <div class="ldr-icon-bg">
            <div class="ldr-logo">
                <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="ldr-text">
        <div class="ldr-name">{{ config('app.name', 'School Management') }}</div>
        <div class="ldr-badge"><span class="ldr-dot"></span>AI Powered<span class="ldr-dots"><span></span><span></span><span></span></span></div>
    </div>
    <div class="ldr-progress"><div class="ldr-bar"></div></div>
</div>

<div style="display:flex;min-height:100vh;">

    {{-- Mobile backdrop --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- ─── Sidebar ─── --}}
    <aside id="sidebar">

        <div class="sb-logo-row">
            <div class="sb-logo-icon">
                <svg style="width:1.125rem;height:1.125rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div style="min-width:0;flex:1;">
                <p class="sb-logo-name">{{ config('app.name', 'School Management') }}</p>
                <p class="sb-logo-sub">Management System</p>
            </div>
            <button class="sb-collapse-btn" onclick="toggleSidebar()" title="Collapse sidebar">
                <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        <nav id="sidebar-nav">
            @php $menuService = app(\App\Services\MenuService::class); @endphp
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
                                {!! $item->icon !!}{{ $item->label }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endforeach

            @php $systemSection = $menuService->getSection('system'); @endphp
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
                            {!! $item->icon !!}{{ $item->label }}
                        </a>
                    @endforeach
                </div>
            @endif
        </nav>

        <div class="sb-user-row">
            <div class="sb-avatar">
                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div style="min-width:0;flex:1;">
                <p class="sb-user-name">{{ auth()->user()?->name ?? 'Administrator' }}</p>
                <p class="sb-user-email">{{ auth()->user()?->email ?? '' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="flex-shrink:0;">
                @csrf
                <button type="submit" class="sb-logout-btn" title="Sign out">
                    <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── Main ─── --}}
    <div id="main-content">

        <header id="topbar">
            {{-- Hamburger --}}
            <button class="topbar-hamburger" onclick="toggleSidebar()" title="Toggle menu" aria-label="Toggle sidebar">
                <svg style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div style="flex:1;min-width:0;">
                @yield('breadcrumb')
            </div>

            <div style="display:flex;align-items:center;gap:.5rem;">
                {{-- Theme toggle --}}
                <button class="theme-toggle" onclick="toggleTheme()" title="Toggle dark/light mode" id="theme-btn">
                    <svg id="theme-icon-sun" style="width:1rem;height:1rem;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9H21M3 12H2m15.07-6.07l-.71.71M7.64 17.36l-.71.71M18.36 17.36l-.71-.71M6.34 6.34l-.71-.71M17 12a5 5 0 11-10 0 5 5 0 0110 0z"/>
                    </svg>
                    <svg id="theme-icon-moon" style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <a id="install-bundle-btn" href="/bundle-installer" class="install-btn">
                    <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Install Bundle</span>
                </a>

                <span id="topbar-date" style="font-size:.75rem;color:var(--text-muted);white-space:nowrap;">{{ now()->format('D, M d Y') }}</span>
            </div>
        </header>

        {{-- Flash toasts --}}
        <div style="position:fixed;top:4.5rem;right:1rem;z-index:50;display:flex;flex-direction:column;gap:.5rem;width:20rem;" id="flash-container">
            @if(session('success'))
            <div class="flash-msg flash-success">
                <div style="width:1.25rem;height:1.25rem;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:.75rem;height:.75rem;color:#16a34a;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p class="flash-title-success">Success</p>
                    <p class="flash-body-success">{{ session('success') }}</p>
                </div>
                <button onclick="this.closest('.flash-msg').remove()" style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:0;line-height:1;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div class="flash-msg flash-error">
                <div style="width:1.25rem;height:1.25rem;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:.75rem;height:.75rem;color:#dc2626;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p class="flash-title-error">Error</p>
                    <p class="flash-body-error">{{ session('error') }}</p>
                </div>
                <button onclick="this.closest('.flash-msg').remove()" style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:0;line-height:1;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif
        </div>

        <main id="page-main">
            @yield('content')
        </main>

        <footer id="page-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'School Management') }}. All rights reserved.</p>
        </footer>
    </div>
</div>

<script>
/* ── Theme ── */
(function () {
    var saved = localStorage.getItem('vidra-theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    updateThemeIcon(saved);
    function updateThemeIcon(t) {
        var sun  = document.getElementById('theme-icon-sun');
        var moon = document.getElementById('theme-icon-moon');
        if (!sun || !moon) return;
        sun.style.display  = t === 'dark' ? 'block' : 'none';
        moon.style.display = t === 'dark' ? 'none'  : 'block';
    }
    window.toggleTheme = function () {
        var cur = document.documentElement.getAttribute('data-theme');
        var next = cur === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('vidra-theme', next);
        updateThemeIcon(next);
    };
})();

/* ── Loader dismiss ── */
(function () {
    function dismissLoader() {
        var l = document.getElementById('page-loader');
        if (!l || l.classList.contains('out')) return;
        l.classList.add('out');
        l.addEventListener('transitionend', function () {
            l.style.display = 'none';
            document.body.classList.remove('loading');
        }, { once: true });
    }
    if (document.readyState === 'complete') setTimeout(dismissLoader, 300);
    else window.addEventListener('load', function () { setTimeout(dismissLoader, 300); });
    setTimeout(dismissLoader, 3000);
})();

/* ── Sidebar ── */
const SB_SECTIONS = ['s-people','s-academics','s-subjects','s-attendance','s-finance','s-timetable','s-communication','s-hostel','s-exams','s-datatransfer','s-rbac','s-system'];

function toggleSection(id) {
    var c = document.getElementById(id);
    var ch = document.getElementById(id + '-ch');
    if (!c) return;
    var open = c.style.display !== 'none';
    c.style.display = open ? 'none' : 'flex';
    if (ch) ch.style.transform = open ? 'rotate(-90deg)' : '';
    try { localStorage.setItem('sb-' + id, open ? 'closed' : 'open'); } catch(e) {}
}

function toggleSidebar() {
    var sidebar  = document.getElementById('sidebar');
    var main     = document.getElementById('main-content');
    var overlay  = document.getElementById('sidebar-overlay');
    var isMobile = window.innerWidth < 768;
    var isOpen   = !sidebar.classList.contains('sidebar-hidden');

    sidebar.classList.toggle('sidebar-hidden', isOpen);

    if (!isMobile) {
        main.classList.toggle('no-sidebar', isOpen);
        try { localStorage.setItem('sb-sidebar', isOpen ? 'closed' : 'open'); } catch(e) {}
    } else {
        /* On mobile: show/hide overlay only, never shift content */
        if (overlay) overlay.classList.toggle('active', !isOpen);
    }
}

document.addEventListener('DOMContentLoaded', function () {

    /* Restore section collapsed states */
    SB_SECTIONS.forEach(function (id) {
        try {
            if (localStorage.getItem('sb-' + id) === 'closed') {
                var c = document.getElementById(id);
                var ch = document.getElementById(id + '-ch');
                if (c) c.style.display = 'none';
                if (ch) ch.style.transform = 'rotate(-90deg)';
            }
        } catch(e) {}
    });

    /* Always expand section with active link */
    var activeLink = document.querySelector('.sidebar-link.active');
    if (activeLink) {
        var sec = activeLink.closest('.section-items');
        if (sec && sec.id) {
            sec.style.display = 'flex';
            var ch = document.getElementById(sec.id + '-ch');
            if (ch) ch.style.transform = '';
            try { localStorage.setItem('sb-' + sec.id, 'open'); } catch(e) {}
        }
    }

    /* Mobile: always start with sidebar hidden */
    if (window.innerWidth < 768) {
        document.getElementById('sidebar').classList.add('sidebar-hidden');
    } else {
        /* Desktop: restore saved state */
        try {
            if (localStorage.getItem('sb-sidebar') === 'closed') {
                document.getElementById('sidebar').classList.add('sidebar-hidden');
                document.getElementById('main-content').classList.add('no-sidebar');
            }
        } catch(e) {}
    }

    /* Auto-close on resize to desktop */
    window.addEventListener('resize', function () {
        var overlay = document.getElementById('sidebar-overlay');
        if (window.innerWidth >= 768 && overlay) {
            overlay.classList.remove('active');
        }
    });

    /* Flash auto-dismiss */
    document.querySelectorAll('.flash-msg').forEach(function (msg) {
        setTimeout(function () {
            msg.style.transition = 'opacity .4s, transform .4s';
            msg.style.opacity = '0';
            msg.style.transform = 'translateX(.75rem)';
            setTimeout(function () { msg.remove(); }, 400);
        }, 5000);
    });

    /* Apply saved theme icon after DOM ready */
    (function () {
        var t = localStorage.getItem('vidra-theme') || 'light';
        var sun  = document.getElementById('theme-icon-sun');
        var moon = document.getElementById('theme-icon-moon');
        if (sun)  sun.style.display  = t === 'dark' ? 'block' : 'none';
        if (moon) moon.style.display = t === 'dark' ? 'none'  : 'block';
    })();
});
</script>
</body>
</html>
