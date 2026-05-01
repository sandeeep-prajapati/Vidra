<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Management System — Next-Gen ERP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; background: #0a0a14; color: #e2e8f0; overflow-x: hidden; }

        /* ── Animations ── */
        @keyframes fadeUp   { from { opacity:0; transform:translateY(32px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn   { from { opacity:0; } to { opacity:1; } }
        @keyframes scaleIn  { from { opacity:0; transform:scale(.92); } to { opacity:1; transform:scale(1); } }
        @keyframes float    { 0%,100%{ transform:translateY(0)   rotate(0deg); }
                              50%    { transform:translateY(-22px) rotate(3deg); } }
        @keyframes floatB   { 0%,100%{ transform:translateY(0)   rotate(0deg); }
                              50%    { transform:translateY(-16px) rotate(-2deg); } }
        @keyframes spinSlow { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
        @keyframes pulse    { 0%,100%{ opacity:.6; transform:scale(1);   }
                              50%     { opacity:1;  transform:scale(1.05); } }
        @keyframes shimmer  { 0%   { background-position:-200% center; }
                              100% { background-position: 200% center; } }
        @keyframes blobMove { 0%,100%{ border-radius:60% 40% 30% 70%/60% 30% 70% 40%; transform:translate(0,0) scale(1); }
                              33%    { border-radius:30% 60% 70% 40%/50% 60% 30% 60%; transform:translate(30px,-20px) scale(1.05); }
                              66%    { border-radius:50% 60% 30% 60%/40% 70% 60% 50%; transform:translate(-20px,15px) scale(.97); } }
        @keyframes gradShift { 0%,100%{ background-position:0% 50%; } 50%{ background-position:100% 50%; } }
        @keyframes countUp  { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideRight { from { opacity:0; transform:translateX(-24px); } to { opacity:1; transform:translateX(0); } }
        @keyframes glow     { 0%,100%{ box-shadow:0 0 20px rgba(99,102,241,.3); }
                              50%     { box-shadow:0 0 40px rgba(99,102,241,.6); } }

        .animate-fade-up   { animation: fadeUp   .7s ease both; }
        .animate-fade-in   { animation: fadeIn   .6s ease both; }
        .animate-scale-in  { animation: scaleIn  .5s ease both; }
        .animate-float     { animation: float    6s ease-in-out infinite; }
        .animate-float-b   { animation: floatB   8s ease-in-out infinite; }
        .animate-pulse-slow{ animation: pulse    3s ease-in-out infinite; }

        /* ── Glass card ── */
        .glass { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); backdrop-filter:blur(12px); }
        .glass:hover { background:rgba(255,255,255,.07); border-color:rgba(99,102,241,.35); }

        /* ── Gradient text ── */
        .grad-text {
            background: linear-gradient(135deg, #818cf8, #c084fc, #e879f9);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }
        .grad-text-blue {
            background: linear-gradient(135deg, #60a5fa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .grad-text-green {
            background: linear-gradient(135deg, #34d399, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .grad-text-orange {
            background: linear-gradient(135deg, #fb923c, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Hero blob bg ── */
        .blob { position:absolute; border-radius:50%; filter:blur(80px); opacity:.25; animation:blobMove 12s ease-in-out infinite; }
        .blob-1 { width:600px; height:600px; background:#4f46e5; top:-200px; left:-150px; animation-delay:0s; }
        .blob-2 { width:500px; height:500px; background:#7c3aed; top:100px; right:-100px; animation-delay:-4s; }
        .blob-3 { width:400px; height:400px; background:#0ea5e9; bottom:0; left:30%; animation-delay:-8s; }

        /* ── Grid dots bg ── */
        .dots-bg {
            background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* ── Section title ── */
        .section-title { font-size:clamp(1.75rem,3vw,2.5rem); font-weight:800; line-height:1.2; }
        .section-sub   { font-size:.9375rem; color:#94a3b8; margin-top:.75rem; max-width:560px; }

        /* ── Module card ── */
        .module-card {
            border-radius:1rem;
            padding:1.5rem;
            transition: transform .25s, box-shadow .25s;
            cursor:default;
        }
        .module-card:hover { transform:translateY(-6px); box-shadow:0 20px 40px rgba(0,0,0,.4); }

        /* ── Feature pill ── */
        .feat-pill { display:inline-flex; align-items:center; gap:.375rem; padding:.25rem .75rem; border-radius:999px; font-size:.75rem; font-weight:500; }

        /* ── Roadmap card ── */
        .road-card { border-radius:1.25rem; padding:2rem; transition:transform .3s; }
        .road-card:hover { transform:translateY(-8px); }

        /* ── Nav ── */
        #topnav { position:fixed; top:0; left:0; right:0; z-index:100;
            background:rgba(10,10,20,.8); backdrop-filter:blur(16px); border-bottom:1px solid rgba(255,255,255,.07); }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background:#0a0a14; }
        ::-webkit-scrollbar-thumb { background:#334155; border-radius:3px; }

        /* ── Delay helpers ── */
        .d-100{ animation-delay:.1s; } .d-200{ animation-delay:.2s; } .d-300{ animation-delay:.3s; }
        .d-400{ animation-delay:.4s; } .d-500{ animation-delay:.5s; } .d-600{ animation-delay:.6s; }
        .d-700{ animation-delay:.7s; } .d-800{ animation-delay:.8s; } .d-900{ animation-delay:.9s; }
    </style>
</head>
<body>

{{-- ── Nav ── --}}
<nav id="topnav">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:4rem;">
        <div style="display:flex;align-items:center;gap:.75rem;">
            <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:.625rem;display:flex;align-items:center;justify-content:center;animation:glow 3s ease-in-out infinite;">
                <svg style="width:1.125rem;height:1.125rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span style="font-weight:700;font-size:.9375rem;color:#f1f5f9;">School<span class="grad-text">ERP</span></span>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;">
            <a href="#modules" style="padding:.4rem .875rem;font-size:.8125rem;color:#94a3b8;text-decoration:none;border-radius:.5rem;transition:color .2s;" onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#94a3b8'">Modules</a>
            <a href="#roadmap" style="padding:.4rem .875rem;font-size:.8125rem;color:#94a3b8;text-decoration:none;border-radius:.5rem;transition:color .2s;" onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#94a3b8'">Roadmap</a>
            <a href="/docs" style="padding:.4rem .875rem;font-size:.8125rem;color:#94a3b8;text-decoration:none;border-radius:.5rem;transition:color .2s;" onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#94a3b8'">API Docs</a>
            <a href="{{ route('students.index') }}" style="padding:.5rem 1.125rem;font-size:.8125rem;font-weight:600;color:#fff;text-decoration:none;border-radius:.5rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);transition:opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                Open App →
            </a>
        </div>
    </div>
</nav>

{{-- ── Hero ── --}}
<section style="position:relative;min-height:100vh;display:flex;align-items:center;overflow:hidden;padding-top:4rem;" class="dots-bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    {{-- Floating orbs --}}
    <div class="animate-float" style="position:absolute;top:15%;right:8%;width:80px;height:80px;background:linear-gradient(135deg,rgba(99,102,241,.4),rgba(168,85,247,.4));border-radius:50%;filter:blur(2px);"></div>
    <div class="animate-float-b" style="position:absolute;top:60%;right:20%;width:48px;height:48px;background:linear-gradient(135deg,rgba(14,165,233,.4),rgba(99,102,241,.3));border-radius:50%;filter:blur(1px);"></div>
    <div class="animate-float" style="position:absolute;top:40%;left:5%;width:60px;height:60px;background:linear-gradient(135deg,rgba(236,72,153,.3),rgba(168,85,247,.3));border-radius:50%;filter:blur(2px);animation-delay:-3s;"></div>

    <div style="position:relative;z-index:10;max-width:1280px;margin:0 auto;padding:5rem 1.5rem;width:100%;">
        <div style="max-width:800px;">
            <div class="animate-fade-up" style="display:inline-flex;align-items:center;gap:.5rem;padding:.375rem 1rem;border-radius:999px;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.3);font-size:.75rem;font-weight:600;color:#a5b4fc;margin-bottom:1.5rem;">
                <span style="width:.5rem;height:.5rem;background:#6366f1;border-radius:50%;animation:pulse 2s ease-in-out infinite;"></span>
                Next-Generation School ERP Platform
            </div>

            <h1 class="animate-fade-up d-100" style="font-size:clamp(2.5rem,6vw,4.5rem);font-weight:900;line-height:1.08;letter-spacing:-.02em;color:#f8fafc;">
                Manage Every Aspect<br>
                of Your School with<br>
                <span class="grad-text">AI-Powered Intelligence</span>
            </h1>

            <p class="animate-fade-up d-200" style="margin-top:1.5rem;font-size:1.0625rem;color:#94a3b8;line-height:1.7;max-width:560px;">
                An all-in-one platform combining <strong style="color:#c084fc;">AI intelligence</strong>, <strong style="color:#60a5fa;">Blockchain security</strong>, and <strong style="color:#34d399;">IoT automation</strong> — reducing administrative workload by 60% while giving real-time visibility into every school operation.
            </p>

            <div class="animate-fade-up d-300" style="display:flex;flex-wrap:wrap;gap:1rem;margin-top:2.5rem;">
                <a href="{{ route('students.index') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.875rem 2rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:.75rem;font-weight:700;font-size:.9375rem;color:#fff;text-decoration:none;transition:transform .2s,box-shadow .2s;box-shadow:0 8px 24px rgba(79,70,229,.35);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(79,70,229,.5)'" onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(79,70,229,.35)'">
                    Launch App
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="/docs" style="display:inline-flex;align-items:center;gap:.5rem;padding:.875rem 2rem;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:.75rem;font-weight:600;font-size:.9375rem;color:#e2e8f0;text-decoration:none;transition:background .2s,border-color .2s;" onmouseover="this.style.background='rgba(255,255,255,.1)';this.style.borderColor='rgba(255,255,255,.2)'" onmouseout="this.style.background='rgba(255,255,255,.06)';this.style.borderColor='rgba(255,255,255,.12)'">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    API Docs
                </a>
            </div>
        </div>

        {{-- Stats row ── --}}
        <div class="animate-fade-up d-400" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-top:5rem;max-width:900px;">
            @foreach([
                ['11', 'Live Modules'],
                ['22+', 'Planned Modules'],
                ['200+', 'API Endpoints'],
                ['15+', 'AI Features'],
                ['10+', 'Blockchain Features'],
                ['20+', 'IoT Features'],
            ] as $stat)
            <div class="glass" style="padding:1.25rem 1rem;border-radius:.875rem;text-align:center;">
                <div style="font-size:1.875rem;font-weight:800;color:#f1f5f9;line-height:1;">{{ $stat[0] }}</div>
                <div style="font-size:.75rem;color:#64748b;margin-top:.25rem;font-weight:500;">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- scroll indicator --}}
    <div style="position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:.375rem;animation:pulse 2s ease-in-out infinite;">
        <span style="font-size:.6875rem;color:#475569;letter-spacing:.08em;text-transform:uppercase;">Scroll</span>
        <svg style="width:1rem;height:1rem;color:#475569;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

{{-- ── Modules ── --}}
<section id="modules" style="padding:6rem 1.5rem;position:relative;background:#0d0d1a;">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:4rem;">
            <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.375rem 1rem;border-radius:999px;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);font-size:.75rem;font-weight:600;color:#818cf8;margin-bottom:1rem;">
                Currently Live
            </div>
            <h2 class="section-title" style="color:#f1f5f9;">11 Powerful Modules</h2>
            <p class="section-sub" style="margin:1rem auto 0;">Every module is fully REST-API backed with Scribe-generated docs, role-based access, and real-time data.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">

            {{-- Student Management --}}
            <div class="glass module-card animate-scale-in d-100">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(99,102,241,.3),rgba(139,92,246,.3));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#a5b4fc;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Student Management</h3>
                        <span style="font-size:.7rem;color:#6366f1;font-weight:500;">Core Module</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Full lifecycle management — profiles, enrollments, promotions, health records, documents, contacts, and activity logs.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Profiles','Enrollments','Promotions','Health Records','Documents','Contacts'] as $f)
                    <span class="feat-pill" style="background:rgba(99,102,241,.12);color:#a5b4fc;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Staff Management --}}
            <div class="glass module-card animate-scale-in d-200">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(16,185,129,.25),rgba(5,150,105,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Staff Management</h3>
                        <span style="font-size:.7rem;color:#10b981;font-weight:500;">HR Module</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Departments, salary management, attendance, leave requests, performance reviews, and credential tracking.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Departments','Salary','Attendance','Leave Requests','Reviews'] as $f)
                    <span class="feat-pill" style="background:rgba(16,185,129,.12);color:#6ee7b7;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Class Management --}}
            <div class="glass module-card animate-scale-in d-300">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(245,158,11,.25),rgba(217,119,6,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#fcd34d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Class Management</h3>
                        <span style="font-size:.7rem;color:#f59e0b;font-weight:500;">Academic Core</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Classes, sections, batches, and academic years with full enrollment and promotion workflow.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Classes','Sections','Batches','Academic Years'] as $f)
                    <span class="feat-pill" style="background:rgba(245,158,11,.12);color:#fcd34d;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Subject & Curriculum --}}
            <div class="glass module-card animate-scale-in d-400">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(14,165,233,.25),rgba(2,132,199,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#7dd3fc;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Subjects & Curriculum</h3>
                        <span style="font-size:.7rem;color:#0ea5e9;font-weight:500;">Learning</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Subject master, class-subject mapping, lesson plans, textbooks, and teacher-subject assignments.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Subjects','Curriculum','Lesson Plans','Textbooks','Teacher Mapping'] as $f)
                    <span class="feat-pill" style="background:rgba(14,165,233,.12);color:#7dd3fc;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Attendance --}}
            <div class="glass module-card animate-scale-in d-500">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(236,72,153,.25),rgba(219,39,119,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#f9a8d4;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Attendance Management</h3>
                        <span style="font-size:.7rem;color:#ec4899;font-weight:500;">Tracking</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Daily student & staff attendance, leave request workflows, holiday calendar, and absence pattern reports.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Student Attendance','Staff Attendance','Leave Requests','Holidays'] as $f)
                    <span class="feat-pill" style="background:rgba(236,72,153,.12);color:#f9a8d4;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Exam Management --}}
            <div class="glass module-card animate-scale-in d-600">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(168,85,247,.25),rgba(139,92,246,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#d8b4fe;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Exam Management</h3>
                        <span style="font-size:.7rem;color:#a855f7;font-weight:500;">Assessment</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Exam creation, scheduling, grading schemes, marks entry, report cards, and performance analytics.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Exams','Schedules','Grading Schemes','Marks','Report Cards'] as $f)
                    <span class="feat-pill" style="background:rgba(168,85,247,.12);color:#d8b4fe;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Fee Management --}}
            <div class="glass module-card animate-scale-in d-700">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(20,184,166,.25),rgba(13,148,136,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#5eead4;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Fee & Finance</h3>
                        <span style="font-size:.7rem;color:#14b8a6;font-weight:500;">Financial</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Fee structures, student fees, payments, discounts, scholarships, expenses, and financial reports.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Fee Structures','Payments','Discounts','Expenses','Reports'] as $f)
                    <span class="feat-pill" style="background:rgba(20,184,166,.12);color:#5eead4;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Timetable --}}
            <div class="glass module-card animate-scale-in d-800">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(249,115,22,.25),rgba(234,88,12,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#fdba74;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Timetable Management</h3>
                        <span style="font-size:.7rem;color:#f97316;font-weight:500;">Scheduling</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Dynamic timetables, periods, rooms, substitute assignments, and special event scheduling.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Timetables','Periods','Rooms','Substitutions','Special Events'] as $f)
                    <span class="feat-pill" style="background:rgba(249,115,22,.12);color:#fdba74;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Communication --}}
            <div class="glass module-card animate-scale-in d-900">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(99,102,241,.3),rgba(59,130,246,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#93c5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Communication</h3>
                        <span style="font-size:.7rem;color:#3b82f6;font-weight:500;">Messaging</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Messages, circulars, notification preferences, and delivery/read receipts for all stakeholders.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Messages','Circulars','Recipients','Notification Prefs'] as $f)
                    <span class="feat-pill" style="background:rgba(59,130,246,.12);color:#93c5fd;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Hostel & Transport --}}
            <div class="glass module-card animate-scale-in d-100">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(52,211,153,.25),rgba(16,185,129,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">Hostel & Transport</h3>
                        <span style="font-size:.7rem;color:#34d399;font-weight:500;">Campus Life</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Hostels, rooms, transport vehicles, routes, facility bookings, and student allocation management.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Hostels','Rooms','Transport','Routes','Facilities','Bookings'] as $f)
                    <span class="feat-pill" style="background:rgba(52,211,153,.12);color:#6ee7b7;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- RBAC --}}
            <div class="glass module-card animate-scale-in d-200">
                <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1rem;">
                    <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(239,68,68,.25),rgba(220,38,38,.25));border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#fca5a5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;">RBAC Management</h3>
                        <span style="font-size:.7rem;color:#ef4444;font-weight:500;">Security</span>
                    </div>
                </div>
                <p style="font-size:.8125rem;color:#64748b;line-height:1.6;margin-bottom:1rem;">Granular roles, permissions, user-role assignments, and permission sync — built on Spatie's permission package.</p>
                <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
                    @foreach(['Roles','Permissions','User Roles','User Permissions'] as $f)
                    <span class="feat-pill" style="background:rgba(239,68,68,.12);color:#fca5a5;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>

            {{-- API Docs Card --}}
            <div style="border-radius:1rem;padding:1.5rem;background:linear-gradient(135deg,rgba(79,70,229,.18),rgba(124,58,237,.18));border:1px solid rgba(99,102,241,.3);display:flex;flex-direction:column;justify-content:space-between;transition:transform .25s;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform=''">
                <div>
                    <div style="font-size:1.5rem;margin-bottom:.75rem;">📄</div>
                    <h3 style="font-size:.9375rem;font-weight:700;color:#f1f5f9;margin-bottom:.5rem;">REST API + Docs</h3>
                    <p style="font-size:.8125rem;color:#64748b;line-height:1.6;">200+ endpoints with Scribe-generated interactive docs, Postman collection, and OpenAPI 3.0 spec.</p>
                </div>
                <div style="display:flex;gap:.625rem;margin-top:1.25rem;flex-wrap:wrap;">
                    <a href="/docs" style="display:inline-flex;align-items:center;gap:.375rem;padding:.4rem .875rem;background:rgba(99,102,241,.25);border:1px solid rgba(99,102,241,.4);border-radius:.5rem;font-size:.75rem;font-weight:600;color:#a5b4fc;text-decoration:none;">HTML Docs</a>
                    <a href="/docs.openapi" style="display:inline-flex;align-items:center;gap:.375rem;padding:.4rem .875rem;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:.5rem;font-size:.75rem;font-weight:600;color:#818cf8;text-decoration:none;">OpenAPI</a>
                    <a href="/docs.postman" style="display:inline-flex;align-items:center;gap:.375rem;padding:.4rem .875rem;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:.5rem;font-size:.75rem;font-weight:600;color:#818cf8;text-decoration:none;">Postman</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Future Roadmap ── --}}
<section id="roadmap" style="padding:6rem 1.5rem;background:#0a0a14;position:relative;" class="dots-bg">
    <div style="max-width:1280px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:4rem;">
            <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.375rem 1rem;border-radius:999px;background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.2);font-size:.75rem;font-weight:600;color:#c084fc;margin-bottom:1rem;">
                Coming Soon
            </div>
            <h2 class="section-title" style="color:#f1f5f9;">The Future Roadmap</h2>
            <p class="section-sub" style="margin:1rem auto 0;">AI, Blockchain, and IoT technologies that will make this platform truly next-generation.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:1.5rem;margin-bottom:3rem;">

            {{-- AI --}}
            <div class="road-card animate-fade-up" style="background:linear-gradient(145deg,#0d1117,#111827);border:1px solid rgba(99,102,241,.2);">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                    <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:1.375rem;animation:float 5s ease-in-out infinite;">🤖</div>
                    <div>
                        <h3 style="font-size:1.125rem;font-weight:800;" class="grad-text-blue">AI Features</h3>
                        <span style="font-size:.75rem;color:#6366f1;font-weight:500;">15+ planned</span>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:.625rem;">
                    @foreach([
                        ['Personalized Learning Paths', 'Recommends materials based on strengths'],
                        ['Dropout Prediction', 'Identify at-risk students early'],
                        ['Performance Forecasting', 'Predict future exam scores'],
                        ['Facial Recognition Attendance', 'Zero proxy — AI + camera'],
                        ['Auto Timetable Generator', 'Conflict-free in 5 minutes'],
                        ['Fee Default Prediction', 'Proactively recover dues'],
                        ['AI Report Card Comments', 'Personalized teacher comments'],
                    ] as [$title, $desc])
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:1.25rem;height:1.25rem;background:rgba(99,102,241,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.125rem;">
                            <svg style="width:.625rem;height:.625rem;color:#818cf8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p style="font-size:.8125rem;font-weight:600;color:#e2e8f0;">{{ $title }}</p>
                            <p style="font-size:.725rem;color:#475569;margin-top:.1rem;">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Blockchain --}}
            <div class="road-card animate-fade-up d-200" style="background:linear-gradient(145deg,#0d1117,#111827);border:1px solid rgba(16,185,129,.2);">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                    <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#059669,#10b981);border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:1.375rem;animation:floatB 6s ease-in-out infinite;">⛓️</div>
                    <div>
                        <h3 style="font-size:1.125rem;font-weight:800;" class="grad-text-green">Blockchain Features</h3>
                        <span style="font-size:.75rem;color:#10b981;font-weight:500;">10+ planned</span>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:.625rem;">
                    @foreach([
                        ['Tamper-Proof Certificates', 'Blockchain-issued, instantly verifiable'],
                        ['Immutable Grade Records', 'Grades that can never be altered'],
                        ['Smart Contract Fee Collection', 'Auto-deduct fees on due dates'],
                        ['Blockchain Attendance Logs', 'Zero manipulation possible'],
                        ['Digital Diploma Wallet', 'Students own their credentials'],
                        ['Blockchain Audit Trail', 'Every action recorded forever'],
                        ['Transparent Scholarships', 'Disbursements on-chain'],
                    ] as [$title, $desc])
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:1.25rem;height:1.25rem;background:rgba(16,185,129,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.125rem;">
                            <svg style="width:.625rem;height:.625rem;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p style="font-size:.8125rem;font-weight:600;color:#e2e8f0;">{{ $title }}</p>
                            <p style="font-size:.725rem;color:#475569;margin-top:.1rem;">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- IoT --}}
            <div class="road-card animate-fade-up d-400" style="background:linear-gradient(145deg,#0d1117,#111827);border:1px solid rgba(249,115,22,.2);">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                    <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#ea580c,#f97316);border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:1.375rem;animation:float 7s ease-in-out infinite;animation-delay:-2s;">📡</div>
                    <div>
                        <h3 style="font-size:1.125rem;font-weight:800;" class="grad-text-orange">IoT Features</h3>
                        <span style="font-size:.75rem;color:#f97316;font-weight:500;">20+ planned</span>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:.625rem;">
                    @foreach([
                        ['GPS Live Bus Tracking', 'Parents see bus location in real-time'],
                        ['RFID Smart ID Cards', 'Attendance + gate entry/exit logs'],
                        ['Smart Classroom Sensors', 'Environmental monitoring & auto-control'],
                        ['Geofencing Bus Alerts', 'Notify when bus enters pickup zones'],
                        ['IoT Smart Room Locks', 'Hostel entry tracking via RFID'],
                        ['Speed & Safety Monitoring', 'Driver accountability alerts'],
                        ['Predictive Maintenance', 'Sensors predict equipment failure'],
                    ] as [$title, $desc])
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:1.25rem;height:1.25rem;background:rgba(249,115,22,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.125rem;">
                            <svg style="width:.625rem;height:.625rem;color:#fdba74;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p style="font-size:.8125rem;font-weight:600;color:#e2e8f0;">{{ $title }}</p>
                            <p style="font-size:.725rem;color:#475569;margin-top:.1rem;">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Planned additional modules ── --}}
        <div class="glass animate-fade-up" style="border-radius:1.25rem;padding:2rem;">
            <h3 style="font-size:1rem;font-weight:700;color:#f1f5f9;margin-bottom:1.25rem;">11 More Modules on the Backlog</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.625rem;">
                @foreach([
                    ['📚', 'Library Management'],
                    ['🎓', 'Alumni Management'],
                    ['📅', 'Event Management'],
                    ['⚖️', 'Grievance & Complaints'],
                    ['📝', 'Online Admissions'],
                    ['🏆', 'Discipline & Behavior'],
                    ['🎭', 'Extracurricular / Clubs'],
                    ['📖', 'Homework & Assignments'],
                    ['👨‍👩‍👧', 'Parent-Teacher Meetings'],
                    ['🖥️', 'Inventory & Assets'],
                    ['📊', 'Analytics Dashboard'],
                ] as [$icon, $name])
                <div style="display:flex;align-items:center;gap:.5rem;padding:.625rem .75rem;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:.625rem;">
                    <span style="font-size:.875rem;">{{ $icon }}</span>
                    <span style="font-size:.75rem;color:#94a3b8;font-weight:500;">{{ $name }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── Tech Stack ── --}}
<section style="padding:5rem 1.5rem;background:#0d0d1a;">
    <div style="max-width:1280px;margin:0 auto;text-align:center;">
        <h2 style="font-size:1.25rem;font-weight:600;color:#475569;margin-bottom:2.5rem;letter-spacing:.05em;text-transform:uppercase;font-size:.75rem;">Built With</h2>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;justify-content:center;">
            @foreach([
                ['⚡', 'Laravel 13', '#ff2d20'],
                ['🐘', 'PHP 8.3', '#4f5b93'],
                ['🎨', 'Tailwind CSS 4', '#06b6d4'],
                ['🔑', 'Spatie RBAC', '#ff6b6b'],
                ['📄', 'Scribe Docs', '#6366f1'],
                ['⚙️', 'Vite 8', '#bd34fe'],
                ['🗄️', 'MySQL', '#00758f'],
                ['🔗', 'RESTful API', '#10b981'],
            ] as [$icon, $label, $color])
            <div class="glass" style="display:inline-flex;align-items:center;gap:.5rem;padding:.625rem 1.25rem;border-radius:.75rem;">
                <span style="font-size:1rem;">{{ $icon }}</span>
                <span style="font-size:.8125rem;font-weight:600;color:#94a3b8;">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section style="padding:6rem 1.5rem;position:relative;overflow:hidden;" class="dots-bg">
    <div class="blob" style="width:500px;height:500px;background:#4f46e5;top:50%;left:50%;transform:translate(-50%,-50%);opacity:.15;"></div>
    <div style="position:relative;z-index:10;max-width:640px;margin:0 auto;text-align:center;">
        <h2 class="animate-fade-up" style="font-size:clamp(2rem,4vw,3rem);font-weight:900;line-height:1.1;color:#f8fafc;">
            Ready to Transform<br>Your School?
        </h2>
        <p class="animate-fade-up d-200" style="margin-top:1rem;font-size:.9375rem;color:#64748b;line-height:1.7;">
            22+ modules, 200+ API endpoints, and a roadmap powered by AI, Blockchain, and IoT — all in one platform.
        </p>
        <div class="animate-fade-up d-300" style="display:flex;flex-wrap:wrap;gap:.75rem;justify-content:center;margin-top:2.5rem;">
            <a href="{{ route('students.index') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:1rem 2.25rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:.875rem;font-weight:700;font-size:1rem;color:#fff;text-decoration:none;box-shadow:0 8px 32px rgba(79,70,229,.4);transition:transform .2s,box-shadow .2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 16px 40px rgba(79,70,229,.5)'" onmouseout="this.style.transform='';this.style.boxShadow='0 8px 32px rgba(79,70,229,.4)'">
                Launch Dashboard
                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="/docs" style="display:inline-flex;align-items:center;gap:.5rem;padding:1rem 2.25rem;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:.875rem;font-weight:600;font-size:1rem;color:#e2e8f0;text-decoration:none;transition:background .2s;" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background='rgba(255,255,255,.06)'">
                View API Docs
            </a>
        </div>
    </div>
</section>

{{-- ── Footer ── --}}
<footer style="border-top:1px solid rgba(255,255,255,.06);padding:2rem 1.5rem;background:#0a0a14;">
    <div style="max-width:1280px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div style="display:flex;align-items:center;gap:.625rem;">
            <div style="width:1.75rem;height:1.75rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:.375rem;display:flex;align-items:center;justify-content:center;">
                <svg style="width:.875rem;height:.875rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <span style="font-size:.8125rem;font-weight:600;color:#475569;">{{ config('app.name', 'School Management') }}</span>
        </div>
        <p style="font-size:.75rem;color:#334155;">&copy; {{ date('Y') }} All rights reserved. Built with Laravel 13 + PHP 8.3.</p>
        <div style="display:flex;gap:1rem;">
            <a href="/docs" style="font-size:.75rem;color:#475569;text-decoration:none;">API Docs</a>
            <a href="/docs.openapi" style="font-size:.75rem;color:#475569;text-decoration:none;">OpenAPI</a>
            <a href="{{ route('students.index') }}" style="font-size:.75rem;color:#475569;text-decoration:none;">Dashboard</a>
        </div>
    </div>
</footer>

<script>
// Intersection Observer for staggered card animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0) scale(1)';
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

document.querySelectorAll('.module-card, .road-card').forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(28px) scale(0.97)';
    el.style.transition = `opacity .5s ease ${i * 60}ms, transform .5s ease ${i * 60}ms`;
    observer.observe(el);
});

// Smooth active nav link on scroll
const sections = document.querySelectorAll('section[id]');
window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;
    sections.forEach(section => {
        const top = section.offsetTop - 80;
        const height = section.offsetHeight;
        const id = section.getAttribute('id');
        const link = document.querySelector(`a[href="#${id}"]`);
        if (link) {
            link.style.color = scrollY >= top && scrollY < top + height ? '#e2e8f0' : '#94a3b8';
        }
    });
}, { passive: true });
</script>

</body>
</html>
