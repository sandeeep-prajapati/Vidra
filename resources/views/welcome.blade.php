<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Vidra') }} — School Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #09090b;
            --bg2:     #0f0f12;
            --border:  rgba(255,255,255,.07);
            --accent:  #7c3aed;
            --accent2: #6d28d9;
            --text:    #fafafa;
            --muted:   #71717a;
            --subtle:  #3f3f46;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }

        /* ── NAV ── */
        .site-header {
            position: sticky; top: 0; z-index: 100;
            background: rgba(9,9,11,.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%;
            height: 60px;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 9px;
            font-size: .9rem; font-weight: 700; color: var(--text);
        }

        .nav-brand-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: linear-gradient(145deg, #6d28d9, #4338ca);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(109,40,217,.35);
            flex-shrink: 0;
        }

        .nav-links {
            display: flex; align-items: center; gap: 6px;
        }

        .nav-links a {
            padding: 6px 14px;
            border-radius: 7px;
            font-size: .82rem; font-weight: 500; color: var(--muted);
            transition: color .15s, background .15s;
        }

        .nav-links a:hover { color: var(--text); background: rgba(255,255,255,.05); }

        .nav-links .btn-login {
            background: var(--accent);
            color: #fff;
            padding: 6px 16px;
            font-weight: 600;
            transition: background .15s, transform .15s;
        }

        .nav-links .btn-login:hover { background: var(--accent2); transform: translateY(-1px); }

        /* hamburger button */
        .nav-menu-btn {
            display: none;
            width: 36px; height: 36px;
            background: rgba(255,255,255,.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            align-items: center; justify-content: center;
            flex-shrink: 0;
            color: var(--muted);
            transition: background .15s, color .15s;
        }

        .nav-menu-btn:hover { background: rgba(255,255,255,.09); color: var(--text); }

        /* animated bars */
        .hbg { display: flex; flex-direction: column; gap: 4px; width: 18px; }
        .hbg span {
            display: block; height: 2px; border-radius: 2px;
            background: currentColor;
            transition: transform .25s ease, opacity .25s ease, width .25s ease;
            transform-origin: center;
        }
        .hbg span:nth-child(1) { width: 18px; }
        .hbg span:nth-child(2) { width: 13px; }
        .hbg span:nth-child(3) { width: 18px; }

        .nav-menu-btn.active .hbg span:nth-child(1) { transform: translateY(6px) rotate(45deg); width: 18px; }
        .nav-menu-btn.active .hbg span:nth-child(2) { opacity: 0; }
        .nav-menu-btn.active .hbg span:nth-child(3) { transform: translateY(-6px) rotate(-45deg); width: 18px; }

        /* collapsible dropdown */
        .nav-collapse {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s cubic-bezier(.4,0,.2,1);
        }

        .nav-collapse.open { max-height: 240px; }

        .nav-collapse-inner {
            padding: 10px 5% 16px;
            display: flex; flex-direction: column; gap: 4px;
            border-top: 1px solid var(--border);
        }

        .nav-collapse-inner a {
            display: block;
            padding: 10px 14px; border-radius: 8px;
            font-size: .88rem; font-weight: 500; color: var(--muted);
            transition: background .15s, color .15s;
        }

        .nav-collapse-inner a:hover { background: rgba(255,255,255,.06); color: var(--text); }

        .nav-collapse-inner .btn-login {
            background: var(--accent); color: #fff;
            font-weight: 700; text-align: center; margin-top: 4px;
        }

        .nav-collapse-inner .btn-login:hover { background: var(--accent2); }

        /* ── HERO ── */
        .hero {
            text-align: center;
            padding: 100px 5% 80px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -120px; left: 50%; transform: translateX(-50%);
            width: 700px; height: 500px;
            background: radial-gradient(ellipse, rgba(109,40,217,.13), transparent 68%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 12px;
            background: rgba(124,58,237,.08);
            border: 1px solid rgba(124,58,237,.18);
            border-radius: 999px;
            font-size: .72rem; font-weight: 600; color: #a78bfa;
            letter-spacing: .06em; text-transform: uppercase;
            margin-bottom: 24px;
        }

        .hero h1 {
            font-size: clamp(2rem, 6vw, 3.8rem);
            font-weight: 900;
            letter-spacing: -.04em;
            line-height: 1.1;
            color: var(--text);
            margin-bottom: 20px;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #a78bfa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: clamp(.9rem, 2vw, 1.08rem);
            color: var(--muted);
            line-height: 1.7;
            max-width: 560px;
            margin: 0 auto 36px;
        }

        .hero-cta {
            display: flex; align-items: center; justify-content: center;
            gap: 12px; flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 11px 24px;
            background: var(--accent); color: #fff;
            font-size: .88rem; font-weight: 700;
            border-radius: 9px; border: none; cursor: pointer;
            transition: background .15s, transform .15s, box-shadow .15s;
        }

        .btn-primary:hover {
            background: var(--accent2);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(109,40,217,.35);
        }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 11px 24px;
            background: transparent; color: var(--text);
            font-size: .88rem; font-weight: 600;
            border-radius: 9px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: background .15s, border-color .15s, transform .15s;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,.05);
            border-color: rgba(255,255,255,.14);
            transform: translateY(-2px);
        }

        /* ── STATS ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--border);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .stat {
            background: var(--bg);
            padding: 28px 20px;
            text-align: center;
        }

        .stat-num {
            font-size: 1.8rem; font-weight: 900;
            color: var(--text); letter-spacing: -.03em;
        }

        .stat-num span { color: var(--accent); }

        .stat-label { font-size: .76rem; color: var(--muted); margin-top: 4px; }

        /* ── SECTION WRAPPER ── */
        .section { padding: 72px 5%; }

        .section-head { text-align: center; margin-bottom: 48px; }

        .section-tag {
            display: inline-block;
            font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
            color: var(--accent); margin-bottom: 10px;
        }

        .section-head h2 {
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 800; letter-spacing: -.03em;
            color: var(--text); margin-bottom: 10px;
        }

        .section-head p { font-size: .9rem; color: var(--muted); max-width: 480px; margin: 0 auto; }

        /* ── MODULES GRID ── */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .module-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            transition: border-color .2s, transform .2s, box-shadow .2s;
            cursor: default;
        }

        .module-card:hover {
            border-color: rgba(124,58,237,.3);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,.3);
        }

        .module-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; margin-bottom: 14px;
        }

        .module-card h3 {
            font-size: .9rem; font-weight: 700; color: var(--text);
            margin-bottom: 6px;
        }

        .module-card p {
            font-size: .78rem; color: var(--muted); line-height: 1.55;
        }

        .module-tags {
            display: flex; flex-wrap: wrap; gap: 5px; margin-top: 12px;
        }

        .module-tag {
            font-size: .66rem; font-weight: 600;
            padding: 2px 8px; border-radius: 4px;
            background: rgba(124,58,237,.08);
            color: #a78bfa;
            border: 1px solid rgba(124,58,237,.15);
        }

        /* ── WHY SECTION ── */
        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .why-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .why-icon {
            font-size: 1.4rem; margin-bottom: 10px;
        }

        .why-card h4 { font-size: .88rem; font-weight: 700; color: var(--text); margin-bottom: 5px; }
        .why-card p  { font-size: .78rem; color: var(--muted); line-height: 1.55; }

        /* ── CTA BANNER ── */
        .cta-banner {
            margin: 0 5% 72px;
            background: linear-gradient(135deg, rgba(109,40,217,.14), rgba(67,56,202,.08));
            border: 1px solid rgba(124,58,237,.2);
            border-radius: 18px;
            padding: 52px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%; transform: translateX(-50%);
            width: 400px; height: 300px;
            background: radial-gradient(ellipse, rgba(109,40,217,.12), transparent 70%);
            pointer-events: none;
        }

        .cta-banner h2 {
            font-size: clamp(1.3rem, 3vw, 2rem);
            font-weight: 800; letter-spacing: -.03em;
            color: var(--text); margin-bottom: 10px;
            position: relative;
        }

        .cta-banner p {
            font-size: .88rem; color: var(--muted);
            margin-bottom: 28px; position: relative;
        }

        .cta-banner .cta-btns {
            display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;
            position: relative;
        }

        /* ── CONTACT STRIP ── */
        .contact-strip {
            display: flex; align-items: center; justify-content: center; gap: 16px;
            padding: 20px 5%;
            border-top: 1px solid var(--border);
            background: var(--bg2);
            flex-wrap: wrap;
            text-align: center;
        }

        .contact-strip-icon {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .contact-strip-label { font-size: .68rem; color: var(--muted); font-weight: 600; letter-spacing: .06em; text-transform: uppercase; }
        .contact-strip-num   { font-size: .92rem; font-weight: 700; color: #22c55e; }

        /* ── FOOTER ── */
        footer {
            border-top: 1px solid var(--border);
            padding: 24px 5%;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .footer-brand {
            display: flex; align-items: center; gap: 8px;
            font-size: .82rem; font-weight: 600; color: var(--muted);
        }

        .footer-brand-icon {
            width: 24px; height: 24px; border-radius: 6px;
            background: linear-gradient(145deg, #6d28d9, #4338ca);
            display: flex; align-items: center; justify-content: center;
        }

        .footer-links {
            display: flex; gap: 20px; flex-wrap: wrap;
        }

        .footer-links a {
            font-size: .78rem; color: var(--subtle);
            transition: color .14s;
        }

        .footer-links a:hover { color: var(--muted); }

        .footer-copy { font-size: .74rem; color: var(--subtle); }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .nav-links { display: none; }
            .nav-menu-btn { display: flex; }
            .hero { padding: 72px 5% 56px; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            .cta-banner { padding: 36px 24px; }
            footer { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 400px) {
            .stats { grid-template-columns: 1fr 1fr; }
            .stat-num { font-size: 1.4rem; }
        }

        /* ── PAGE LOADER ── */
        #page-loader {
            position: fixed; inset: 0; z-index: 9999;
            background: #09090b;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 0;
            transition: opacity .55s ease, transform .55s ease;
        }

        #page-loader.out {
            opacity: 0;
            transform: scale(1.06);
            pointer-events: none;
        }

        /* pulse rings */
        .ldr-rings {
            position: absolute;
            width: 180px; height: 180px;
        }

        .ldr-ring {
            position: absolute; inset: 0;
            border-radius: 50%;
            border: 1px solid rgba(124,58,237,.35);
            animation: ldr-pulse 2.4s ease-out infinite;
        }

        .ldr-ring:nth-child(2) { animation-delay: .8s; }
        .ldr-ring:nth-child(3) { animation-delay: 1.6s; }

        @keyframes ldr-pulse {
            0%   { transform: scale(.55); opacity: .8; }
            100% { transform: scale(1.6);  opacity: 0; }
        }

        /* spinning arc */
        .ldr-arc-wrap {
            position: relative;
            width: 84px; height: 84px;
            flex-shrink: 0;
        }

        .ldr-arc {
            position: absolute; inset: 0;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #7c3aed 0%, #a78bfa 30%, transparent 60%);
            animation: ldr-spin 1.1s linear infinite;
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 3.5px), #000 0);
            mask:         radial-gradient(farthest-side, transparent calc(100% - 3.5px), #000 0);
        }

        @keyframes ldr-spin {
            to { transform: rotate(360deg); }
        }

        /* icon center */
        .ldr-icon-bg {
            position: absolute; inset: 8px;
            border-radius: 50%;
            background: #0f0f12;
            border: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; justify-content: center;
        }

        /* text block */
        .ldr-text {
            margin-top: 28px;
            text-align: center;
            animation: ldr-fadein .6s ease both;
            animation-delay: .2s;
        }

        @keyframes ldr-fadein {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .ldr-name {
            font-size: 1.25rem; font-weight: 800;
            color: #fafafa; letter-spacing: -.03em;
            margin-bottom: 8px;
        }

        /* AI badge */
        .ldr-badge {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 5px 12px;
            background: rgba(124,58,237,.08);
            border: 1px solid rgba(124,58,237,.2);
            border-radius: 999px;
            font-size: .72rem; font-weight: 600; color: #a78bfa;
            letter-spacing: .05em; text-transform: uppercase;
        }

        /* animated green dot */
        .ldr-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #22c55e;
            animation: ldr-blink 1.1s ease-in-out infinite;
            flex-shrink: 0;
        }

        @keyframes ldr-blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .3; transform: scale(.7); }
        }

        /* typing dots */
        .ldr-dots { display: inline-flex; gap: 3px; margin-left: 2px; }
        .ldr-dots span {
            width: 3px; height: 3px; border-radius: 50%;
            background: #a78bfa;
            animation: ldr-dot-bounce .9s ease-in-out infinite;
        }
        .ldr-dots span:nth-child(2) { animation-delay: .15s; }
        .ldr-dots span:nth-child(3) { animation-delay: .3s; }

        @keyframes ldr-dot-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: .4; }
            40%            { transform: translateY(-4px); opacity: 1; }
        }

        /* progress bar */
        .ldr-progress {
            margin-top: 32px;
            width: 160px; height: 2px;
            background: rgba(255,255,255,.06);
            border-radius: 2px;
            overflow: hidden;
        }

        .ldr-progress-bar {
            height: 100%; width: 0%;
            background: linear-gradient(90deg, #6d28d9, #a78bfa);
            border-radius: 2px;
            animation: ldr-fill 2s cubic-bezier(.4,0,.2,1) forwards;
        }

        @keyframes ldr-fill {
            0%   { width: 0%; }
            60%  { width: 75%; }
            85%  { width: 88%; }
            100% { width: 100%; }
        }

        /* body hidden during load */
        body.loading { overflow: hidden; }
    </style>
</head>
<body class="loading">

<!-- ══ PAGE LOADER ══ -->
<div id="page-loader" role="status" aria-label="Loading">

    <!-- pulse rings (behind) -->
    <div class="ldr-rings">
        <div class="ldr-ring"></div>
        <div class="ldr-ring"></div>
        <div class="ldr-ring"></div>
    </div>

    <!-- spinning arc + icon -->
    <div class="ldr-arc-wrap">
        <div class="ldr-arc"></div>
        <div class="ldr-icon-bg">
            <svg width="22" height="22" fill="none" stroke="#a78bfa" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
    </div>

    <!-- text -->
    <div class="ldr-text">
        <div class="ldr-name">{{ config('app.name', 'Vidra') }}</div>
        <div class="ldr-badge">
            <span class="ldr-dot"></span>
            AI Powered
            <span class="ldr-dots">
                <span></span><span></span><span></span>
            </span>
        </div>
    </div>

    <!-- progress bar -->
    <div class="ldr-progress">
        <div class="ldr-progress-bar"></div>
    </div>

</div>

<!-- ══ HEADER ══ -->
<header class="site-header">
<nav>
    <div class="nav-brand">
        <div class="nav-brand-icon">
            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        {{ config('app.name', 'Vidra') }}
    </div>

    <div class="nav-links">
        <a href="{{ url('/docs') }}">Docs</a>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-login" style="border-radius:7px;">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-login" style="border-radius:7px;">Sign in</a>
        @endauth
    </div>

    <button class="nav-menu-btn" id="nav-toggle" onclick="toggleNav()" aria-label="Menu" aria-expanded="false">
        <div class="hbg">
            <span></span><span></span><span></span>
        </div>
    </button>
</nav>

<!-- collapsible dropdown -->
<div class="nav-collapse" id="nav-collapse">
    <div class="nav-collapse-inner">
        <a href="{{ url('/docs') }}">Docs</a>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-login" style="border-radius:8px;">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-login" style="border-radius:8px;">Sign in</a>
        @endauth
    </div>
</div>
</header>

<!-- ══ HERO ══ -->
<section class="hero">
    <div class="hero-badge">
        <svg width="9" height="9" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
        School Management System
    </div>
    <h1>
        Everything your school needs,<br>
        <span>in one platform.</span>
    </h1>
    <p class="hero-desc">
        Vidra brings together 13 powerful modules — from student admissions and fee collection to timetabling, exams, and real-time webhooks — so you can run your institution without juggling spreadsheets.
    </p>
    <div class="hero-cta">
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Go to Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Sign in
            </a>
        @endauth
        <a href="{{ url('/docs') }}" class="btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            View Docs
        </a>
    </div>
</section>

<!-- ══ STATS ══ -->
<div class="stats">
    <div class="stat">
        <div class="stat-num">13<span>+</span></div>
        <div class="stat-label">Core Modules</div>
    </div>
    <div class="stat">
        <div class="stat-num">100<span>+</span></div>
        <div class="stat-label">Features</div>
    </div>
    <div class="stat">
        <div class="stat-num">3</div>
        <div class="stat-label">Built-in Roles</div>
    </div>
    <div class="stat">
        <div class="stat-num">∞</div>
        <div class="stat-label">Bundle Support</div>
    </div>
</div>

<!-- ══ MODULES ══ -->
<section class="section">
    <div class="section-head">
        <div class="section-tag">Core Modules</div>
        <h2>Everything built in, nothing bolted on</h2>
        <p>All 13 modules ship together — no add-ons required. Each one integrates natively with the rest.</p>
    </div>

    <div class="modules-grid">

        <div class="module-card">
            <div class="module-icon" style="background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.18);">🎓</div>
            <h3>Student Management</h3>
            <p>Admissions, profiles, guardian records, section assignments, attendance history, and academic results all in one place.</p>
            <div class="module-tags">
                <span class="module-tag">Admissions</span>
                <span class="module-tag">Profiles</span>
                <span class="module-tag">Results</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(6,182,212,.08);border:1px solid rgba(6,182,212,.16);">👨‍🏫</div>
            <h3>Staff Management</h3>
            <p>Manage teacher and support staff records, departments, employment types, and leave balances from a unified interface.</p>
            <div class="module-tags">
                <span class="module-tag">Teachers</span>
                <span class="module-tag">Departments</span>
                <span class="module-tag">Leave</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.16);">🏫</div>
            <h3>Class Management</h3>
            <p>Create classes, sections, and assign class teachers. Control student capacity and organize your school structure.</p>
            <div class="module-tags">
                <span class="module-tag">Classes</span>
                <span class="module-tag">Sections</span>
                <span class="module-tag">Teachers</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.16);">📚</div>
            <h3>Subject Management</h3>
            <p>Define subjects, link them to classes and academic years, manage textbooks, and map qualified teachers to each subject.</p>
            <div class="module-tags">
                <span class="module-tag">Subjects</span>
                <span class="module-tag">Curriculum</span>
                <span class="module-tag">Textbooks</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.16);">📅</div>
            <h3>Timetable Management</h3>
            <p>Build class schedules with rooms, periods, and days. Handle substitute assignments and one-off special events easily.</p>
            <div class="module-tags">
                <span class="module-tag">Periods</span>
                <span class="module-tag">Rooms</span>
                <span class="module-tag">Substitutes</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.16);">📝</div>
            <h3>Exam Management</h3>
            <p>Schedule exams, record marks, generate grade-based results, and produce student report cards automatically.</p>
            <div class="module-tags">
                <span class="module-tag">Exams</span>
                <span class="module-tag">Marks</span>
                <span class="module-tag">Report Cards</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.16);">💰</div>
            <h3>Fee Management</h3>
            <p>Create fee structures, collect payments, apply discounts and waivers, and track outstanding balances with full financial reporting.</p>
            <div class="module-tags">
                <span class="module-tag">Payments</span>
                <span class="module-tag">Discounts</span>
                <span class="module-tag">Reports</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(251,146,60,.08);border:1px solid rgba(251,146,60,.16);">✅</div>
            <h3>Attendance Management</h3>
            <p>Mark daily student and staff attendance, view session-wise records, and generate attendance summary reports by class.</p>
            <div class="module-tags">
                <span class="module-tag">Daily</span>
                <span class="module-tag">Sessions</span>
                <span class="module-tag">Reports</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(14,165,233,.08);border:1px solid rgba(14,165,233,.16);">💬</div>
            <h3>Communication</h3>
            <p>Send announcements, SMS alerts, and email notifications. Manage notice boards and broadcast messages to parents and staff.</p>
            <div class="module-tags">
                <span class="module-tag">Notices</span>
                <span class="module-tag">SMS</span>
                <span class="module-tag">Email</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(168,85,247,.08);border:1px solid rgba(168,85,247,.16);">🚌</div>
            <h3>Hostel & Transport</h3>
            <p>Manage hostel rooms and assign students. Configure transport routes, vehicles, and bus stop assignments with fees.</p>
            <div class="module-tags">
                <span class="module-tag">Hostel</span>
                <span class="module-tag">Routes</span>
                <span class="module-tag">Vehicles</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(234,179,8,.08);border:1px solid rgba(234,179,8,.16);">🔐</div>
            <h3>Role-Based Access</h3>
            <p>Fine-grained permissions for every user. Assign custom roles, restrict routes, and control what each staff member can see or do.</p>
            <div class="module-tags">
                <span class="module-tag">Roles</span>
                <span class="module-tag">Permissions</span>
                <span class="module-tag">Users</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(20,184,166,.08);border:1px solid rgba(20,184,166,.16);">🔄</div>
            <h3>Data Transfer</h3>
            <p>Import students and staff from CSV, export records to spreadsheets, and migrate data between academic years in bulk.</p>
            <div class="module-tags">
                <span class="module-tag">Import</span>
                <span class="module-tag">Export</span>
                <span class="module-tag">Migration</span>
            </div>
        </div>

        <div class="module-card">
            <div class="module-icon" style="background:rgba(236,72,153,.08);border:1px solid rgba(236,72,153,.16);">🔗</div>
            <h3>Webhooks</h3>
            <p>Receive real-time HTTP callbacks when school events occur. Configure endpoints, monitor logs, and debug failed deliveries.</p>
            <div class="module-tags">
                <span class="module-tag">Real-time</span>
                <span class="module-tag">Logs</span>
                <span class="module-tag">Events</span>
            </div>
        </div>

    </div>
</section>

<!-- ══ WHY VIDRA ══ -->
<section class="section" style="padding-top:0;">
    <div class="section-head">
        <div class="section-tag">Why Vidra</div>
        <h2>Built for real schools</h2>
        <p>Designed from the ground up for the way school administrators actually work.</p>
    </div>

    <div class="why-grid">
        <div class="why-card">
            <div class="why-icon">⚡</div>
            <h4>Instant Setup</h4>
            <p>Go from zero to running in minutes. No complex configuration — sensible defaults ship out of the box.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">🧩</div>
            <h4>Bundle System</h4>
            <p>Extend Vidra with custom bundles. Upload a ZIP and the system registers routes, permissions, and menus automatically.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">🔒</div>
            <h4>Permission-First</h4>
            <p>Every route is protected. Role-based access control is built into every module — no taping it on afterwards.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">📊</div>
            <h4>Full Audit Trails</h4>
            <p>Track every action — payments, attendance, exam results, and webhook deliveries — with timestamps and logs.</p>
        </div>
    </div>
</section>

<!-- ══ CTA BANNER ══ -->
<div class="cta-banner">
    <h2>Ready to simplify your school?</h2>
    <p>Sign in with the demo account to explore every module — no setup required.</p>
    <div class="cta-btns">
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-primary">Go to Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Sign in to demo
            </a>
        @endauth
        <a href="{{ url('/docs') }}" class="btn-secondary">Browse Documentation</a>
    </div>
</div>

<!-- ══ CONTACT STRIP ══ -->
<div class="contact-strip">
    <div class="contact-strip-icon">
        <svg width="15" height="15" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
        </svg>
    </div>
    <div>
        <div class="contact-strip-label">Need help?</div>
        <div class="contact-strip-num">+91 63924 24180</div>
    </div>
    <div style="font-size:.8rem;color:var(--muted);">Call us for support &amp; enquiries</div>
</div>

<!-- ══ FOOTER ══ -->
<footer>
    <div class="footer-brand">
        <div class="footer-brand-icon">
            <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        {{ config('app.name', 'Vidra') }}
    </div>

    <div class="footer-links">
        <a href="{{ url('/docs') }}">Documentation</a>
        @auth
            <a href="{{ url('/dashboard') }}">Dashboard</a>
        @else
            <a href="{{ route('login') }}">Sign in</a>
        @endauth
    </div>

    <div class="footer-copy">&copy; {{ date('Y') }} {{ config('app.name', 'Vidra') }}. School ERP.</div>
</footer>

<script>
/* ── Loader dismiss ── */
(function () {
    function dismissLoader() {
        const loader = document.getElementById('page-loader');
        if (!loader) return;
        loader.classList.add('out');
        loader.addEventListener('transitionend', function () {
            loader.style.display = 'none';
            document.body.classList.remove('loading');
        }, { once: true });
    }

    // Dismiss after progress bar completes (~2.1s), or immediately if page already loaded
    if (document.readyState === 'complete') {
        setTimeout(dismissLoader, 400);
    } else {
        window.addEventListener('load', function () {
            setTimeout(dismissLoader, 400);
        });
    }
    // Hard cap: never show loader more than 3.5s regardless
    setTimeout(dismissLoader, 3500);
})();

function toggleNav() {
    const btn      = document.getElementById('nav-toggle');
    const collapse = document.getElementById('nav-collapse');
    const isOpen   = collapse.classList.toggle('open');
    btn.classList.toggle('active', isOpen);
    btn.setAttribute('aria-expanded', isOpen);
}

// Close on outside click
document.addEventListener('click', function (e) {
    const btn      = document.getElementById('nav-toggle');
    const collapse = document.getElementById('nav-collapse');
    const header   = document.querySelector('.site-header');
    if (!header.contains(e.target) && collapse.classList.contains('open')) {
        collapse.classList.remove('open');
        btn.classList.remove('active');
        btn.setAttribute('aria-expanded', 'false');
    }
});

// Close on Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const btn      = document.getElementById('nav-toggle');
        const collapse = document.getElementById('nav-collapse');
        collapse.classList.remove('open');
        btn.classList.remove('active');
        btn.setAttribute('aria-expanded', 'false');
    }
});

// On resize to desktop, close the dropdown
window.addEventListener('resize', function () {
    if (window.innerWidth > 640) {
        const btn      = document.getElementById('nav-toggle');
        const collapse = document.getElementById('nav-collapse');
        collapse.classList.remove('open');
        btn.classList.remove('active');
        btn.setAttribute('aria-expanded', 'false');
    }
});
</script>

</body>
</html>
