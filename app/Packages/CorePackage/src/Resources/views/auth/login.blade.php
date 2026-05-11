<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — {{ config('app.name', 'School Management') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #09090b;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Left panel ── */
        .l-panel {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 46%;
            min-height: 100vh;
            padding: 40px 48px;
            background: linear-gradient(160deg, #0f0520 0%, #09090b 60%);
            border-right: 1px solid rgba(255,255,255,.06);
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 1024px) { .l-panel { display: flex; } }

        /* decorative grid */
        .l-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,58,237,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* glow orb */
        .l-panel::after {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(109,40,217,.18), transparent 70%);
            pointer-events: none;
        }

        .l-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .l-brand-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(145deg, #6d28d9, #4338ca);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(109,40,217,.4), inset 0 1px 0 rgba(255,255,255,.12);
        }

        .l-brand-name { font-size: .9rem; font-weight: 700; color: #f4f4f5; }
        .l-brand-sub  { font-size: .65rem; color: #71717a; text-transform: uppercase; letter-spacing: .06em; margin-top: 1px; }

        /* center content */
        .l-center { position: relative; z-index: 1; }

        .l-headline {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 800;
            color: #fafafa;
            letter-spacing: -.03em;
            line-height: 1.18;
            margin-bottom: 14px;
        }

        .l-headline span {
            background: linear-gradient(135deg, #a78bfa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .l-desc { font-size: .84rem; color: #71717a; line-height: 1.7; max-width: 320px; margin-bottom: 32px; }

        /* feature list */
        .l-features { display: flex; flex-direction: column; gap: 12px; }

        .l-feat {
            display: flex; align-items: flex-start; gap: 12px;
        }

        .l-feat-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .l-feat-text { font-size: .8rem; color: #a1a1aa; line-height: 1.5; }
        .l-feat-title { font-weight: 600; color: #d4d4d8; display: block; margin-bottom: 1px; }

        /* bottom */
        .l-bottom { position: relative; z-index: 1; }

        .l-contact-card {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            padding: 14px 16px;
            display: flex; align-items: center; gap: 12px;
        }

        .l-contact-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(34,197,94,.1);
            border: 1px solid rgba(34,197,94,.2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .l-contact-label { font-size: .65rem; color: #52525b; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; margin-bottom: 2px; }
        .l-contact-num   { font-size: .88rem; font-weight: 700; color: #22c55e; letter-spacing: .01em; }
        .l-contact-sub   { font-size: .68rem; color: #52525b; margin-top: 1px; }

        /* ── Right panel (form) ── */
        .r-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            background: #09090b;
            min-height: 100vh;
        }

        .form-wrap {
            width: 100%;
            max-width: 380px;
        }

        /* Mobile brand */
        .mob-brand {
            display: flex; align-items: center; gap: 9px;
            margin-bottom: 28px;
        }

        @media (min-width: 1024px) { .mob-brand { display: none; } }

        .mob-brand-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: linear-gradient(145deg, #6d28d9, #4338ca);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(109,40,217,.3);
        }

        .mob-brand-name { font-size: .88rem; font-weight: 700; color: #fafafa; }

        /* Form header */
        .form-heading {
            margin-bottom: 24px;
        }

        .form-heading h1 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #fafafa;
            letter-spacing: -.02em;
            margin-bottom: 4px;
        }

        .form-heading p { font-size: .8rem; color: #71717a; }

        /* Error banner */
        .err-banner {
            display: flex; align-items: center; gap: 10px;
            background: rgba(239,68,68,.07);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: 9px;
            padding: 11px 14px;
            margin-bottom: 18px;
        }

        .err-banner svg { flex-shrink: 0; color: #f87171; }
        .err-banner p   { font-size: .79rem; color: #f87171; font-weight: 500; }

        /* Demo credentials */
        .demo-box {
            background: rgba(255,255,255,.025);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }

        .demo-box-label {
            display: flex; align-items: center; gap: 5px;
            font-size: .62rem; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: #71717a;
            margin-bottom: 10px;
        }

        /* role tabs */
        .demo-tabs {
            display: flex; gap: 5px;
            margin-bottom: 10px;
        }

        .demo-tab {
            flex: 1;
            padding: 5px 4px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,.07);
            background: transparent;
            font-size: .69rem; font-weight: 600;
            color: #52525b;
            cursor: pointer;
            transition: all .15s;
            text-align: center;
        }

        .demo-tab:hover { background: rgba(255,255,255,.04); color: #a1a1aa; }

        .demo-tab.active-admin      { background: rgba(124,58,237,.12); border-color: rgba(124,58,237,.3);  color: #c4b5fd; }
        .demo-tab.active-student    { background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.25); color: #6ee7b7; }
        .demo-tab.active-teacher    { background: rgba(14,165,233,.1);  border-color: rgba(14,165,233,.25); color: #7dd3fc; }
        .demo-tab.active-librarian  { background: rgba(245,158,11,.1);  border-color: rgba(245,158,11,.25); color: #fcd34d; }
        .demo-tab.active-accountant { background: rgba(5,150,105,.1);   border-color: rgba(5,150,105,.25);  color: #6ee7b7; }

        .demo-pane { display: none; }
        .demo-pane.active { display: block; }

        .demo-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 5px;
        }
        .demo-row:last-of-type { margin-bottom: 0; }

        .demo-key { font-size: .73rem; color: #52525b; font-weight: 500; flex-shrink: 0; }

        .demo-val {
            font-size: .7rem; color: #a1a1aa;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
            padding: 1px 6px;
            border-radius: 4px;
            max-width: 190px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .demo-fill-btn {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            width: 100%; margin-top: 9px;
            padding: 6px;
            background: transparent;
            border: 1px dashed rgba(255,255,255,.1);
            border-radius: 7px;
            font-size: .72rem; font-weight: 600; color: #52525b;
            cursor: pointer;
            transition: all .15s;
        }

        .demo-fill-btn:hover {
            background: rgba(255,255,255,.04);
            border-color: rgba(255,255,255,.18);
            color: #a1a1aa;
        }

        /* Form fields */
        .field { margin-bottom: 14px; }

        .field label {
            display: block;
            font-size: .77rem; font-weight: 500; color: #a1a1aa;
            margin-bottom: 5px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 11px; top: 50%;
            transform: translateY(-50%);
            color: #3f3f46;
            pointer-events: none;
        }

        .field input[type="email"],
        .field input[type="password"] {
            width: 100%;
            padding: 9px 12px 9px 36px;
            background: #0f0f12;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 8px;
            font-size: .84rem;
            color: #f4f4f5;
            font-family: inherit;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .field input:focus {
            border-color: rgba(124,58,237,.45);
            box-shadow: 0 0 0 3px rgba(124,58,237,.08);
        }

        .field input.err { border-color: rgba(239,68,68,.4); }
        .field input::placeholder { color: #3f3f46; }

        /* Password toggle */
        .pw-wrap { position: relative; }
        .pw-toggle {
            position: absolute; right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #52525b; padding: 2px;
            transition: color .15s;
        }
        .pw-toggle:hover { color: #a1a1aa; }

        /* Remember + options */
        .form-opts {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }

        .remember {
            display: flex; align-items: center; gap: 7px;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 14px; height: 14px;
            accent-color: #7c3aed;
            cursor: pointer;
        }

        .remember span { font-size: .78rem; color: #71717a; }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 10px 16px;
            background: #7c3aed;
            color: #fff;
            font-size: .84rem; font-weight: 700;
            border: none; border-radius: 9px;
            cursor: pointer;
            transition: background .15s, transform .15s, box-shadow .15s;
            letter-spacing: .01em;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }

        .btn-submit:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(109,40,217,.35);
        }

        .btn-submit:active { transform: translateY(0); }

        /* Help + back */
        .form-footer { margin-top: 22px; text-align: center; }

        .form-footer a {
            font-size: .76rem; color: #52525b;
            text-decoration: none;
            transition: color .14s;
        }

        .form-footer a:hover { color: #a1a1aa; }

        /* Mobile contact */
        .mob-contact {
            display: flex; align-items: center; gap: 8px;
            margin-top: 18px;
            padding: 10px 14px;
            background: rgba(34,197,94,.05);
            border: 1px solid rgba(34,197,94,.12);
            border-radius: 9px;
        }

        @media (min-width: 1024px) { .mob-contact { display: none; } }

        .mob-contact svg { color: #22c55e; flex-shrink: 0; }
        .mob-contact-info { }
        .mob-contact-label { font-size: .63rem; color: #52525b; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; }
        .mob-contact-num   { font-size: .82rem; font-weight: 700; color: #22c55e; }
    </style>
</head>
<body>

<!-- ══ LEFT PANEL ══ -->
<div class="l-panel">

    <!-- Brand -->
    <div class="l-brand">
        <div class="l-brand-icon">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <div class="l-brand-name">{{ config('app.name', 'OpenVidra') }}</div>
            <div class="l-brand-sub">School ERP</div>
        </div>
    </div>

    <!-- Center copy -->
    <div class="l-center">
        <h2 class="l-headline">
            The smarter way to<br>
            run your <span>school</span>.
        </h2>
        <p class="l-desc">
            Manage students, staff, fees, exams, attendance, and timetables — all in one AI-powered platform.
        </p>

        <div class="l-features">
            <div class="l-feat">
                <div class="l-feat-icon" style="background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.2);">🎓</div>
                <div class="l-feat-text">
                    <span class="l-feat-title">Student Management</span>
                    Admissions, profiles, attendance and results in one place.
                </div>
            </div>
            <div class="l-feat">
                <div class="l-feat-icon" style="background:rgba(6,182,212,.1);border:1px solid rgba(6,182,212,.18);">📅</div>
                <div class="l-feat-text">
                    <span class="l-feat-title">Timetable & Exams</span>
                    Automated scheduling, substitutions and report cards.
                </div>
            </div>
            <div class="l-feat">
                <div class="l-feat-icon" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.18);">💰</div>
                <div class="l-feat-text">
                    <span class="l-feat-title">Fee Management</span>
                    Fee structures, payments, discounts and financial reports.
                </div>
            </div>
            <div class="l-feat">
                <div class="l-feat-icon" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.18);">🔐</div>
                <div class="l-feat-text">
                    <span class="l-feat-title">Role-Based Access</span>
                    Fine-grained permissions for every staff member.
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="l-bottom">
        <div class="l-contact-card">
            <div class="l-contact-icon">
                <svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <div>
                <div class="l-contact-label">Need help?</div>
                <div class="l-contact-num">+91 63924 24180</div>
                <div class="l-contact-sub">Call us for support &amp; enquiries</div>
            </div>
        </div>
    </div>

</div>

<!-- ══ RIGHT PANEL (form) ══ -->
<div class="r-panel">
    <div class="form-wrap">

        <!-- Mobile brand -->
        <div class="mob-brand">
            <div class="mob-brand-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="mob-brand-name">{{ config('app.name', 'OpenVidra') }}</div>
        </div>

        <!-- Heading -->
        <div class="form-heading">
            <h1>Welcome back</h1>
            <p>Sign in as admin, teacher or student</p>
        </div>

        <!-- Error -->
        @if($errors->any())
        <div class="err-banner">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4M12 16h.01"/>
            </svg>
            <p>{{ $errors->first() }}</p>
        </div>
        @endif

        <!-- Demo credentials -->
        <div class="demo-box">
            <div class="demo-box-label">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Demo Credentials
            </div>

            {{-- Role tabs --}}
            <div class="demo-tabs" style="flex-wrap:wrap;">
                <button class="demo-tab active-admin" type="button" onclick="switchDemo('admin')">Admin</button>
                <button class="demo-tab" type="button" onclick="switchDemo('student')">Student</button>
                <button class="demo-tab" type="button" onclick="switchDemo('teacher')">Teacher</button>
                <button class="demo-tab" type="button" onclick="switchDemo('librarian')">Librarian</button>
                <button class="demo-tab" type="button" onclick="switchDemo('accountant')">Accountant</button>
            </div>

            {{-- Admin pane --}}
            <div class="demo-pane active" id="demo-admin">
                <div class="demo-row"><span class="demo-key">Email</span><span class="demo-val">admin@school.com</span></div>
                <div class="demo-row"><span class="demo-key">Password</span><span class="demo-val">admin123</span></div>
                <button class="demo-fill-btn" type="button" onclick="fillDemo('admin@school.com','admin123')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Use Admin account
                </button>
            </div>

            {{-- Student pane --}}
            <div class="demo-pane" id="demo-student">
                <div class="demo-row"><span class="demo-key">Email</span><span class="demo-val" title="ishaan.verma.student@school.com">ishaan.verma.student@school.com</span></div>
                <div class="demo-row"><span class="demo-key">Password</span><span class="demo-val">student123</span></div>
                <button class="demo-fill-btn" type="button" onclick="fillDemo('ishaan.verma.student@school.com','student123')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Use Student account
                </button>
            </div>

            {{-- Teacher pane --}}
            <div class="demo-pane" id="demo-teacher">
                <div class="demo-row"><span class="demo-key">Email</span><span class="demo-val" title="anjali.sharma.teacher@school.com">anjali.sharma.teacher@school.com</span></div>
                <div class="demo-row"><span class="demo-key">Password</span><span class="demo-val">teacher123</span></div>
                <button class="demo-fill-btn" type="button" onclick="fillDemo('anjali.sharma.teacher@school.com','teacher123')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Use Teacher account
                </button>
            </div>

            {{-- Librarian pane --}}
            <div class="demo-pane" id="demo-librarian">
                <div class="demo-row"><span class="demo-key">Email</span><span class="demo-val">librarian@school.com</span></div>
                <div class="demo-row"><span class="demo-key">Password</span><span class="demo-val">librarian123</span></div>
                <button class="demo-fill-btn" type="button" onclick="fillDemo('librarian@school.com','librarian123')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Use Librarian account
                </button>
            </div>

            {{-- Accountant pane --}}
            <div class="demo-pane" id="demo-accountant">
                <div class="demo-row"><span class="demo-key">Email</span><span class="demo-val">accountant@school.com</span></div>
                <div class="demo-row"><span class="demo-key">Password</span><span class="demo-val">accountant123</span></div>
                <button class="demo-fill-btn" type="button" onclick="fillDemo('accountant@school.com','accountant123')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Use Accountant account
                </button>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="field">
                <label for="email">Email address</label>
                <div class="input-wrap">
                    <svg class="input-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        class="{{ $errors->has('email') ? 'err' : '' }}"
                        placeholder="admin@school.com"
                    >
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap pw-wrap">
                    <svg class="input-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        class="{{ $errors->has('password') ? 'err' : '' }}"
                        placeholder="••••••••"
                    >
                    <button type="button" class="pw-toggle" onclick="togglePw()" id="pw-btn" title="Show/hide password">
                        <svg id="pw-eye" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-opts">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Sign in
            </button>
        </form>

        <!-- Mobile contact -->
        <div class="mob-contact">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <div class="mob-contact-info">
                <div class="mob-contact-label">Need help?</div>
                <div class="mob-contact-num">+91 63924 24180</div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('home') }}">← Back to home</a>
        </div>

    </div>
</div>

<script>
var _demoActive = 'admin';
var _tabClasses = {
    admin:      'active-admin',
    student:    'active-student',
    teacher:    'active-teacher',
    librarian:  'active-librarian',
    accountant: 'active-accountant'
};
var _tabIdx = { admin: 0, student: 1, teacher: 2, librarian: 3, accountant: 4 };

function switchDemo(role) {
    var oldTab = document.querySelector('.demo-tab.' + _tabClasses[_demoActive]);
    if (oldTab) oldTab.classList.remove(_tabClasses[_demoActive]);
    document.getElementById('demo-' + _demoActive).classList.remove('active');

    _demoActive = role;
    var tabs = document.querySelectorAll('.demo-tab');
    tabs[_tabIdx[role]].classList.add(_tabClasses[role]);
    document.getElementById('demo-' + role).classList.add('active');
}

function fillDemo(email, password) {
    document.getElementById('email').value    = email;
    document.getElementById('password').value = password;
}

function togglePw() {
    const pw  = document.getElementById('password');
    const eye = document.getElementById('pw-eye');
    const show = pw.type === 'password';
    pw.type = show ? 'text' : 'password';
    eye.innerHTML = show
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}
</script>

</body>
</html>
