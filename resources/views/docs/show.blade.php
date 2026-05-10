<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ Str::headline(basename($currentPage)) }} — OpenVidra Docs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    :root {
        --sw: 268px;
        --hh: 52px;
        --accent: #7c3aed;
        --accent-dim: rgba(124,58,237,.08);
        --accent-border: rgba(124,58,237,.18);
        --bg: #09090b;
        --surface: #0f0f12;
        --surface2: #141418;
        --border: rgba(255,255,255,.06);
        --border2: rgba(255,255,255,.03);
        --t1: #fafafa;
        --t2: #a1a1aa;
        --t3: #52525b;
        --t4: #3f3f46;
    }

    body {
        background: var(--bg);
        color: var(--t2);
        font-family: 'Inter', system-ui, sans-serif;
        overflow-x: hidden;
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(124,58,237,.35); border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(124,58,237,.6); }

    /* Reading progress */
    #prog {
        position: fixed; top: 0; left: 0;
        height: 2px; width: 0%;
        background: linear-gradient(90deg, #7c3aed, #a855f7);
        z-index: 9999;
        transition: width .08s linear;
    }

    /* ══════════════════════════════
       SIDEBAR
    ══════════════════════════════ */
    #sb {
        position: fixed;
        top: 0; left: 0;
        width: var(--sw);
        height: 100vh;
        background: var(--surface);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        z-index: 300;
        transition: transform .26s cubic-bezier(.4,0,.2,1);
    }

    @media (max-width: 1023px) {
        #sb { transform: translateX(-100%); }
        #sb.open { transform: translateX(0); box-shadow: 24px 0 60px rgba(0,0,0,.5); }
    }

    /* Logo */
    .sb-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 16px 14px;
        border-bottom: 1px solid var(--border);
        text-decoration: none;
        flex-shrink: 0;
    }

    .sb-logo-mark {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: linear-gradient(145deg, #6d28d9, #4338ca);
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
        box-shadow: 0 1px 0 rgba(255,255,255,.1) inset, 0 4px 12px rgba(109,40,217,.3);
    }

    .sb-logo-text { min-width: 0; }
    .sb-logo-name { font-size: .88rem; font-weight: 700; color: var(--t1); display: block; }
    .sb-logo-sub  {
        font-size: .62rem; color: var(--t3);
        font-weight: 500; letter-spacing: .04em;
        text-transform: uppercase;
        display: block; margin-top: 1px;
    }

    /* Search */
    .sb-search {
        padding: 10px 12px;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
    }

    .sb-search-box {
        display: flex; align-items: center; gap: 7px;
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 6px 10px;
        transition: border-color .15s;
    }

    .sb-search-box:focus-within {
        border-color: var(--accent-border);
    }

    .sb-search-box svg { color: var(--t4); flex-shrink: 0; }

    #sbq {
        flex: 1; background: none; border: none; outline: none;
        font-size: .76rem; color: var(--t2); font-family: inherit;
    }

    #sbq::placeholder { color: var(--t4); }

    .sb-kbd {
        font-size: .58rem; color: var(--t4);
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 1px 5px;
        font-family: 'JetBrains Mono', monospace;
        flex-shrink: 0;
    }

    /* Nav */
    .sb-nav {
        flex: 1; overflow-y: auto;
        padding: 8px 8px 4px;
        scrollbar-width: thin;
        scrollbar-color: rgba(124,58,237,.2) transparent;
    }

    /* Group */
    .sb-grp { margin-bottom: 1px; }

    .sb-grp-hdr {
        display: flex; align-items: center; gap: 7px;
        padding: 6px 8px;
        cursor: pointer;
        user-select: none;
        border-radius: 7px;
        transition: background .15s;
    }

    .sb-grp-hdr:hover { background: rgba(255,255,255,.03); }

    .sb-grp-icon {
        width: 18px; height: 18px;
        border-radius: 5px;
        display: flex; align-items: center; justify-content: center;
        font-size: .62rem;
        flex-shrink: 0;
    }

    .sb-grp-label {
        flex: 1;
        font-size: .62rem; font-weight: 700;
        letter-spacing: .09em; text-transform: uppercase;
    }

    .sb-grp-arr {
        font-size: .55rem; color: var(--t4);
        transition: transform .18s;
    }

    .sb-grp.closed .sb-grp-arr { transform: rotate(-90deg); }
    .sb-grp.closed .sb-grp-links { display: none; }

    .sb-grp-links { padding: 2px 0 4px 4px; }

    /* Links */
    .sb-link {
        display: flex; align-items: center; gap: 8px;
        padding: 6.5px 10px;
        border-radius: 7px;
        font-size: .78rem; font-weight: 500;
        color: var(--t3);
        text-decoration: none;
        margin-bottom: 1px;
        transition: color .14s, background .14s;
        position: relative;
    }

    .sb-link:hover { color: var(--t2); background: rgba(255,255,255,.04); }

    .sb-link.on {
        color: #a78bfa;
        background: var(--accent-dim);
        font-weight: 600;
    }

    .sb-dot {
        width: 4px; height: 4px;
        border-radius: 50%;
        background: currentColor;
        opacity: .3;
        flex-shrink: 0;
        transition: opacity .14s;
    }

    .sb-link.on .sb-dot { opacity: 1; background: var(--accent); width: 5px; height: 5px; }

    /* Footer */
    .sb-foot {
        padding: 10px 12px;
        border-top: 1px solid var(--border);
        flex-shrink: 0;
    }

    .sb-status {
        display: flex; align-items: center; gap: 6px;
        font-size: .62rem; color: var(--t3);
        margin-bottom: 8px;
    }

    .status-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #22c55e;
        flex-shrink: 0;
    }

    .sb-cta {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        width: 100%;
        padding: 8px 14px;
        background: var(--accent);
        color: #fff;
        font-size: .77rem; font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: opacity .15s, transform .15s;
        letter-spacing: .01em;
    }

    .sb-cta:hover { opacity: .88; transform: translateY(-1px); }

    /* ══════════════════════════════
       MAIN
    ══════════════════════════════ */
    #main {
        margin-left: var(--sw);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 1023px) { #main { margin-left: 0; } }

    /* ══════════════════════════════
       HEADER
    ══════════════════════════════ */
    #hdr {
        position: sticky; top: 0; z-index: 200;
        height: var(--hh);
        background: rgba(9,9,11,.85);
        border-bottom: 1px solid var(--border);
        backdrop-filter: blur(20px) saturate(160%);
        -webkit-backdrop-filter: blur(20px);
        display: flex; align-items: center;
        padding: 0 22px;
        gap: 12px;
    }

    .hdr-burger {
        display: none;
        align-items: center; justify-content: center;
        width: 32px; height: 32px;
        border-radius: 7px;
        background: var(--surface2);
        border: 1px solid var(--border);
        cursor: pointer;
        color: var(--t3);
        transition: color .14s, background .14s;
        flex-shrink: 0;
    }

    .hdr-burger:hover { color: var(--t2); background: rgba(255,255,255,.07); }

    @media (max-width: 1023px) { .hdr-burger { display: flex; } }

    .hdr-crumb {
        display: flex; align-items: center; gap: 6px;
        font-size: .75rem; color: var(--t3);
        flex: 1; min-width: 0;
    }

    .hdr-crumb a { color: inherit; text-decoration: none; transition: color .14s; }
    .hdr-crumb a:hover { color: var(--t2); }
    .hdr-crumb-sep { color: var(--t4); font-size: .7rem; }
    .hdr-crumb-cur { color: var(--t2); font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .hdr-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

    .hdr-demo {
        display: flex; align-items: center; gap: 5px;
        padding: 5px 12px;
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: #a78bfa;
        font-size: .72rem; font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: background .15s, color .15s;
        font-family: inherit;
        white-space: nowrap;
    }

    .hdr-demo:hover { background: rgba(124,58,237,.14); color: #c4b5fd; }

    .hdr-home {
        padding: 5px 12px;
        background: var(--surface2);
        border: 1px solid var(--border);
        color: var(--t3);
        font-size: .72rem; font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
        transition: color .14s, background .14s;
        white-space: nowrap;
    }

    .hdr-home:hover { color: var(--t2); background: rgba(255,255,255,.06); }

    /* ══════════════════════════════
       PAGE TITLE STRIP
    ══════════════════════════════ */
    .pg-title {
        border-bottom: 1px solid var(--border);
        padding: 28px 32px 24px;
    }

    @media (max-width: 640px) { .pg-title { padding: 20px 18px 18px; } }

    .pg-title-inner {
        max-width: 820px;
        margin: 0 auto;
    }

    .pg-label {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 9px;
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        border-radius: 5px;
        font-size: .62rem; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase;
        color: #a78bfa;
        margin-bottom: 10px;
    }

    .pg-h1 {
        font-size: clamp(1.35rem, 3.5vw, 1.9rem);
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.025em;
        line-height: 1.22;
        margin-bottom: 6px;
    }

    .pg-sub { font-size: .81rem; color: var(--t3); }

    /* ══════════════════════════════
       CONTENT
    ══════════════════════════════ */
    #content {
        flex: 1;
        padding: 32px 32px 64px;
        max-width: 852px;
        width: 100%;
        margin: 0 auto;
    }

    @media (max-width: 640px) { #content { padding: 22px 18px 48px; } }

    /* ══════════════════════════════
       VIDEO CARD
    ══════════════════════════════ */
    .vc {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        cursor: pointer;
        margin-bottom: 32px;
        border: 1px solid rgba(255,255,255,.07);
        background: #000;
        transition: border-color .2s;
    }

    .vc:hover { border-color: rgba(124,58,237,.3); }

    .vc video { width: 100%; display: block; max-height: 380px; object-fit: cover; pointer-events: none; }

    .vc-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,.32);
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }

    .vc:hover .vc-overlay { background: rgba(0,0,0,.18); }

    .vc-play {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: rgba(124,58,237,.9);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 24px rgba(124,58,237,.45);
        transition: transform .2s, box-shadow .2s;
    }

    .vc:hover .vc-play { transform: scale(1.08); box-shadow: 0 8px 32px rgba(124,58,237,.6); }

    .vc-bar {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 28px 16px 14px;
        background: linear-gradient(to top, rgba(0,0,0,.75), transparent);
        display: flex; align-items: center; gap: 8px;
    }

    .vc-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px;
        background: rgba(124,58,237,.75);
        border-radius: 5px;
        font-size: .68rem; font-weight: 600;
        color: #fff;
        backdrop-filter: blur(6px);
    }

    .vc-hint { font-size: .69rem; color: rgba(255,255,255,.5); }

    .rdot {
        width: 5px; height: 5px; border-radius: 50%;
        background: #e879f9;
        animation: blink 2s ease-in-out infinite;
    }

    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.25} }

    /* ══════════════════════════════
       VIDEO MODAL
    ══════════════════════════════ */
    #vmodal {
        position: fixed; inset: 0; z-index: 800;
        background: rgba(0,0,0,.88);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        display: flex; align-items: center; justify-content: center;
        padding: 16px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .24s ease;
    }

    #vmodal.show { opacity: 1; pointer-events: all; }

    .vm-box {
        width: 100%; max-width: 1100px;
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        position: relative;
        border: 1px solid rgba(255,255,255,.08);
        box-shadow: 0 40px 100px rgba(0,0,0,.8);
        transform: scale(.94) translateY(20px);
        transition: transform .3s cubic-bezier(.34,1.36,.64,1);
    }

    #vmodal.show .vm-box { transform: scale(1) translateY(0); }

    .vm-bar {
        position: absolute; top: 0; left: 0; right: 0;
        padding: 12px 16px;
        background: linear-gradient(to bottom, rgba(0,0,0,.72), transparent);
        display: flex; align-items: center; justify-content: space-between;
        z-index: 10;
    }

    .vm-title {
        display: flex; align-items: center; gap: 7px;
        font-size: .75rem; font-weight: 600; color: rgba(255,255,255,.75);
    }

    .vm-close {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.14);
        color: rgba(255,255,255,.8); font-size: .8rem;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }

    .vm-close:hover { background: rgba(255,255,255,.18); }

    .vm-box video { width: 100%; display: block; }

    /* ══════════════════════════════
       PROSE
    ══════════════════════════════ */
    .prose { line-height: 1.8; font-size: .89rem; }

    .prose h1 {
        font-size: clamp(1.45rem, 4vw, 2rem);
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.025em;
        line-height: 1.2;
        margin-bottom: .85rem;
    }

    .prose h2 {
        font-size: clamp(1rem, 3vw, 1.25rem);
        font-weight: 700;
        color: var(--t1);
        margin-top: 2.8rem;
        margin-bottom: .7rem;
        padding-bottom: .6rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 8px;
    }

    .prose h2::before {
        content: '';
        width: 3px; height: 1em;
        background: var(--accent);
        border-radius: 2px;
        flex-shrink: 0;
        display: inline-block;
    }

    .prose h3 {
        font-size: clamp(.88rem, 2.5vw, 1rem);
        font-weight: 600;
        color: #c4b5fd;
        margin-top: 1.8rem;
        margin-bottom: .45rem;
    }

    .prose h4 {
        font-size: .87rem; font-weight: 600;
        color: var(--t2);
        margin-top: 1.2rem; margin-bottom: .35rem;
    }

    .prose p { color: var(--t2); margin-bottom: .9rem; }
    .prose strong { color: var(--t1); font-weight: 600; }
    .prose em { color: #a5b4fc; font-style: italic; }
    .prose a { color: #a78bfa; text-underline-offset: 3px; }
    .prose a:hover { color: #c4b5fd; }

    .prose hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 2rem 0;
    }

    /* Unordered */
    .prose ul { list-style: none; margin: 0 0 1rem; }
    .prose ul li {
        position: relative;
        padding-left: 1.25rem;
        margin-bottom: .4rem;
        color: var(--t2);
    }
    .prose ul li::before {
        content: '';
        position: absolute;
        left: 1px; top: .65em;
        width: 5px; height: 5px;
        background: var(--accent);
        border-radius: 1px;
        transform: rotate(45deg);
    }

    /* Ordered — numbered badges */
    .prose ol { list-style: none; margin: 0 0 1rem; counter-reset: s; }
    .prose ol li {
        position: relative;
        padding-left: 1.9rem;
        margin-bottom: .5rem;
        color: var(--t2);
        counter-increment: s;
    }
    .prose ol li::before {
        content: counter(s);
        position: absolute;
        left: 0; top: .1em;
        width: 18px; height: 18px;
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        border-radius: 5px;
        font-size: .6rem; font-weight: 700;
        color: #a78bfa;
        display: flex; align-items: center; justify-content: center;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Blockquote */
    .prose blockquote {
        border-left: 2px solid var(--accent);
        background: var(--accent-dim);
        padding: 12px 16px;
        border-radius: 0 8px 8px 0;
        margin: 1.2rem 0;
        color: #a5b4fc;
        font-style: italic;
        font-size: .86rem;
    }

    /* Code inline */
    .prose code {
        background: rgba(124,58,237,.1);
        color: #c084fc;
        padding: 1.5px 6px;
        border-radius: 4px;
        font-size: .8rem;
        font-family: 'JetBrains Mono', monospace;
        border: 1px solid rgba(124,58,237,.12);
    }

    /* Code block */
    .prose pre {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px 18px;
        overflow-x: auto;
        margin: 1.2rem 0;
        font-size: .79rem;
        line-height: 1.7;
    }

    .prose pre code { background: none; border: none; padding: 0; color: #e2e8f0; font-size: inherit; }

    /* Tables */
    .prose table {
        width: 100%; border-collapse: collapse;
        margin: 1.3rem 0; font-size: .82rem;
        display: block; overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 9px;
    }
    .prose th { background: var(--surface2); color: var(--t1); padding: 9px 13px; text-align: left; font-weight: 600; white-space: nowrap; border-bottom: 1px solid var(--border); }
    .prose td { padding: 8px 13px; border-top: 1px solid var(--border2); white-space: nowrap; }

    .prose img { width: 100%; border-radius: 10px; margin: 1.4rem 0; border: 1px solid var(--border); }

    /* Page footer */
    .pg-foot {
        margin-top: 3.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        font-size: .72rem; color: var(--t4);
    }

    .pg-foot a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        font-size: .74rem;
        display: flex; align-items: center; gap: 4px;
        transition: color .14s;
    }

    .pg-foot a:hover { color: #a78bfa; }

    /* Mobile overlay */
    #mob-ov {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.6);
        z-index: 250;
        backdrop-filter: blur(3px);
    }

    #mob-ov.show { display: block; }

    /* Scroll-to-top */
    #stb {
        position: fixed;
        bottom: 20px; right: 20px;
        width: 36px; height: 36px;
        border-radius: 8px;
        background: var(--surface2);
        border: 1px solid var(--border);
        color: var(--t3);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        z-index: 400;
        opacity: 0; pointer-events: none;
        transition: opacity .2s, color .15s, background .15s;
    }

    #stb.vis { opacity: 1; pointer-events: all; }
    #stb:hover { background: rgba(124,58,237,.12); color: #a78bfa; border-color: var(--accent-border); }
    </style>
</head>
<body>

<div id="prog"></div>
<div id="mob-ov"></div>

<!-- ══════════════════════════════
     VIDEO MODAL (not auto-open)
══════════════════════════════ -->
@if($video)
<div id="vmodal">
    <div class="vm-box">
        <div class="vm-bar">
            <div class="vm-title">
                <div class="rdot"></div>
                Demo Walkthrough — {{ Str::headline(basename($currentPage)) }}
            </div>
            <button class="vm-close" id="vmclose">✕</button>
        </div>
        <video id="vmvid" muted loop playsinline controls>
            <source src="{{ $video }}" type="video/mp4">
        </video>
    </div>
</div>
@endif

<!-- ══════════════════════════════
     SIDEBAR
══════════════════════════════ -->
<aside id="sb">

    <a href="/" class="sb-logo">
        <div class="sb-logo-mark">📚</div>
        <div class="sb-logo-text">
            <span class="sb-logo-name">OpenVidra</span>
            <span class="sb-logo-sub">Documentation</span>
        </div>
    </a>

    <div class="sb-search">
        <div class="sb-search-box">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <input id="sbq" type="text" placeholder="Search…" autocomplete="off"/>
            <span class="sb-kbd">⌘K</span>
        </div>
    </div>

    <nav class="sb-nav">

        @php
            $grpCfg = [
                'getting-started' => ['icon'=>'🚀','color'=>'#22c55e','bg'=>'rgba(34,197,94,.1)'],
                'core-modules'    => ['icon'=>'⚙️', 'color'=>'#a78bfa','bg'=>'rgba(167,139,250,.1)'],
                'premium-modules' => ['icon'=>'💎','color'=>'#f59e0b','bg'=>'rgba(245,158,11,.1)'],
                'api'             => ['icon'=>'🔌','color'=>'#38bdf8','bg'=>'rgba(56,189,248,.1)'],
            ];
        @endphp

        @foreach($menu as $section => $items)
            @php $g = $grpCfg[$section] ?? ['icon'=>'📄','color'=>'#71717a','bg'=>'rgba(113,113,122,.08)']; @endphp

            <div class="sb-grp" data-g="{{ $section }}">
                <div class="sb-grp-hdr" onclick="tg(this)">
                    <div class="sb-grp-icon" style="background:{{ $g['bg'] }}">{{ $g['icon'] }}</div>
                    <span class="sb-grp-label" style="color:{{ $g['color'] }}">{{ Str::headline($section) }}</span>
                    <span class="sb-grp-arr" style="color:{{ $g['color'] }}">▾</span>
                </div>
                <div class="sb-grp-links">
                    @foreach($items as $item)
                        @php $active = request()->url() === $item['url']; @endphp
                        <a href="{{ $item['url'] }}"
                           class="sb-link {{ $active ? 'on' : '' }}"
                           data-q="{{ strtolower($item['title']) }}">
                            <span class="sb-dot"></span>
                            {{ $item['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

    </nav>

    <div class="sb-foot">
        <div class="sb-status">
            <div class="status-dot"></div>
            All systems operational
        </div>
        <a href="/login" class="sb-cta">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Open Application
        </a>
    </div>

</aside>

<!-- ══════════════════════════════
     MAIN
══════════════════════════════ -->
<div id="main">

    <header id="hdr">
        <button class="hdr-burger" id="burger">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/>
            </svg>
        </button>

        <nav class="hdr-crumb">
            <a href="/manual/getting-started/readme">Docs</a>
            <span class="hdr-crumb-sep">›</span>
            <span class="hdr-crumb-cur">{{ Str::headline(basename($currentPage)) }}</span>
        </nav>

        <div class="hdr-right">
            @if($video)
            <button class="hdr-demo" id="openvm">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6.3 2.84A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.27l9.344-5.891a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                </svg>
                Watch Demo
            </button>
            @endif
            <a href="/" class="hdr-home">← Home</a>
        </div>
    </header>

    <!-- Page title -->
    <div class="pg-title">
        <div class="pg-title-inner">
            <div class="pg-label">
                <svg width="8" height="8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Module Guide
            </div>
            <h1 class="pg-h1">{{ Str::headline(basename($currentPage)) }}</h1>
            <p class="pg-sub">Step-by-step guide with demo walkthrough</p>
        </div>
    </div>

    <div id="content">

        <!-- Video card -->
        @if($video)
        <div class="vc" onclick="openvm()">
            <video id="thumbv" muted loop playsinline preload="metadata">
                <source src="{{ $video }}" type="video/mp4">
            </video>
            <div class="vc-overlay">
                <div class="vc-play">
                    <svg width="24" height="24" fill="white" viewBox="0 0 20 20" style="margin-left:2px">
                        <path d="M6.3 2.84A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.27l9.344-5.891a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                    </svg>
                </div>
            </div>
            <div class="vc-bar">
                <div class="vc-badge">
                    <div class="rdot"></div>
                    Interactive Demo
                </div>
                <span class="vc-hint">Click to watch</span>
            </div>
        </div>
        @endif

        <article class="prose">
            {!! $content !!}
        </article>

        <div class="pg-foot">
            <span>OpenVidra Documentation</span>
            <a href="/login">
                Open App
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

    </div>

</div>

<button id="stb" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<script>
// Progress bar + scroll-to-top
const prog = document.getElementById('prog');
const stb  = document.getElementById('stb');
window.addEventListener('scroll', () => {
    const d = document.documentElement;
    prog.style.width = (d.scrollTop / (d.scrollHeight - d.clientHeight) * 100) + '%';
    stb.classList.toggle('vis', d.scrollTop > 380);
}, { passive: true });

// Sidebar groups
function tg(el) { el.closest('.sb-grp').classList.toggle('closed'); }

// Mobile sidebar
const sb  = document.getElementById('sb');
const ov  = document.getElementById('mob-ov');
const btn = document.getElementById('burger');
const openSB  = () => { sb.classList.add('open'); ov.classList.add('show'); document.body.style.overflow = 'hidden'; };
const closeSB = () => { sb.classList.remove('open'); ov.classList.remove('show'); document.body.style.overflow = ''; };
btn?.addEventListener('click', openSB);
ov?.addEventListener('click', closeSB);
let tx = 0;
document.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
document.addEventListener('touchend', e => { if (tx - e.changedTouches[0].clientX > 55 && sb.classList.contains('open')) closeSB(); }, { passive: true });

// Search
document.getElementById('sbq')?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.sb-link').forEach(a => {
        a.style.display = !q || a.dataset.q?.includes(q) || a.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
    if (q) document.querySelectorAll('.sb-grp').forEach(g => g.classList.remove('closed'));
});
document.addEventListener('keydown', e => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') { e.preventDefault(); document.getElementById('sbq')?.focus(); }
});

// Video modal
const vmodal = document.getElementById('vmodal');
const vmvid  = document.getElementById('vmvid');
const thumbv = document.getElementById('thumbv');

function openvm() {
    if (!vmodal) return;
    vmodal.classList.add('show');
    document.body.style.overflow = 'hidden';
    if (vmvid) { vmvid.currentTime = 0; vmvid.play().catch(() => {}); }
    if (thumbv) thumbv.pause();
}

function closevm() {
    if (!vmodal) return;
    vmodal.classList.remove('show');
    document.body.style.overflow = '';
    if (vmvid) vmvid.pause();
    if (thumbv) thumbv.play().catch(() => {});
}

document.getElementById('vmclose')?.addEventListener('click', closevm);
document.getElementById('openvm')?.addEventListener('click', openvm);
vmodal?.addEventListener('click', e => { if (e.target === vmodal) closevm(); });

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (vmodal?.classList.contains('show')) closevm();
        else closeSB();
    }
});

// Silently autoplay muted thumbnail
thumbv?.play().catch(() => {});
</script>

</body>
</html>
