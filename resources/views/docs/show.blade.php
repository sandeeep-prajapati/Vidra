<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <title>OpenVidra Docs</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: #050816;
            color: white;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0b1120;
        }

        ::-webkit-scrollbar-thumb {
            background: #7c3aed;
            border-radius: 999px;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .markdown {
            color: #cbd5e1;
            line-height: 1.7;
            overflow-wrap: break-word;
            font-size: 0.95rem;
        }

        .markdown h1 {
            font-size: clamp(1.5rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding-bottom: 12px;
        }

        .markdown h2 {
            font-size: clamp(1.25rem, 4vw, 2rem);
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #c084fc;
        }

        .markdown h3 {
            font-size: clamp(1rem, 3vw, 1.35rem);
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            color: #67e8f9;
        }

        .markdown p {
            margin-bottom: 1rem;
            color: #cbd5e1;
            line-height: 1.7;
        }

        .markdown strong {
            color: white;
        }

        .markdown ul,
        .markdown ol {
            margin-left: 20px;
            margin-bottom: 1rem;
        }

        .markdown ul {
            list-style: disc;
        }

        .markdown ol {
            list-style: decimal;
        }

        .markdown li {
            margin-bottom: 8px;
        }

        .markdown a {
            color: #a855f7;
            word-break: break-word;
        }

        .markdown a:hover {
            text-decoration: underline;
        }

        .markdown hr {
            border-color: rgba(255,255,255,.08);
            margin: 1.5rem 0;
        }

        .markdown table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            border-radius: 16px;
            font-size: 0.85rem;
        }

        .markdown th {
            background: #111827;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .markdown td {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,.05);
        }

        .markdown pre {
            background: #020617;
            border: 1px solid rgba(124,58,237,.2);
            border-radius: 16px;
            padding: 16px;
            overflow-x: auto;
            margin: 20px 0;
            font-size: 0.8rem;
        }

        .markdown code {
            background: rgba(124,58,237,.12);
            color: #c084fc;
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .markdown pre code {
            background: transparent;
            padding: 0;
            font-size: 0.8rem;
        }

        .markdown img {
            width: 100%;
            border-radius: 16px;
            margin: 20px 0;
            border: 1px solid rgba(255,255,255,.06);
            box-shadow: 0 10px 30px rgba(0,0,0,.4);
        }

        .overlay {
            display: none;
        }

        .overlay.active {
            display: block;
        }

        @media(max-width:1024px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }

            .sidebar-mobile.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

<div class="flex min-h-screen">

    <!-- Overlay -->
    <div id="overlay"
         class="overlay fixed inset-0 bg-black/50 z-40 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed lg:relative
               z-50
               top-0 left-0
               h-screen
               w-[85%] sm:w-64 lg:w-80
               bg-[#080d1f]
               border-r border-purple-900/20
               flex flex-col
               transition-all duration-300
               sidebar-mobile
               lg:translate-x-0">

        <!-- Logo -->
        <div class="p-4 border-b border-purple-900/20 flex items-center justify-between">

            <a href="/" class="block">
                <h1 class="text-xl sm:text-2xl font-black">
                    🚀
                    <span class="text-purple-400">
                        OpenVidra
                    </span>
                </h1>

                <p class="text-slate-400 mt-1 text-xs">
                    AI School ERP Docs
                </p>
            </a>

            <button id="closeSidebarBtn"
                    class="lg:hidden text-slate-400 hover:text-white p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

        </div>

        <!-- Sidebar Menu -->
        <div class="sidebar-scroll flex-1 overflow-y-auto p-3">

            @php
                $colors = [
                    'purple',
                    'cyan',
                    'orange',
                    'emerald',
                    'pink',
                    'blue'
                ];
            @endphp

            @foreach($menu as $section => $items)

                @php
                    $color =
                    $colors[$loop->index % count($colors)];
                @endphp

                <details open class="mb-3">

                    <summary
                        class="cursor-pointer rounded-xl
                               px-4 py-3.5 font-semibold text-sm
                               bg-{{ $color }}-500/10
                               text-{{ $color }}-300
                               hover:bg-{{ $color }}-500/20
                               transition">

                        {{ Str::headline($section) }}
                    </summary>

                    <div class="mt-2 space-y-1.5 pl-2">

                        @foreach($items as $item)

                            <a href="{{ $item['url'] }}"
                               class="block px-4 py-3 rounded-xl transition text-sm
                               {{ request()->url() == $item['url']
                               ? 'bg-purple-600/20 border border-purple-500/20 text-purple-300'
                               : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                                {{ $item['title'] }}
                            </a>

                        @endforeach

                    </div>

                </details>

            @endforeach

        </div>

    </aside>

    <!-- Main -->
    <main class="flex-1 min-w-0 overflow-y-auto lg:pl-0">

        <!-- Header -->
        <header class="sticky top-0 z-30
                       bg-[#050816]/90
                       backdrop-blur-xl
                       border-b border-white/5">

            <div class="px-4 sm:px-6 lg:px-8 py-3 sm:py-4
                        flex flex-col sm:flex-row
                        items-start sm:items-center
                        justify-between gap-3">

                <div class="flex items-center gap-3">

                    <button id="menuBtn"
                        class="lg:hidden bg-slate-800
                               hover:bg-slate-700
                               p-2.5 rounded-xl transition">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <h2 class="text-xl sm:text-2xl font-bold">
                        Documentation
                    </h2>

                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

                    <a href="/"
                       class="text-center
                              bg-slate-800
                              hover:bg-slate-700
                              px-4 py-2.5 rounded-xl transition text-sm sm:text-base">

                        Website
                    </a>

                    <a href="/login"
                       class="text-center
                              bg-gradient-to-r
                              from-purple-600
                              to-violet-500
                              hover:scale-105
                              transition
                              px-4 py-2.5
                              rounded-xl
                              font-semibold text-sm sm:text-base">

                        Open App →
                    </a>

                </div>

            </div>

        </header>

        <!-- Hero -->
        <section class="px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-10 pb-4 sm:pb-6">

            <div class="bg-gradient-to-r
                        from-purple-700/20
                        to-cyan-600/10
                        border border-white/10
                        rounded-[24px] sm:rounded-[32px]
                        p-5 sm:p-6 lg:p-8">

                <h1 class="text-2xl sm:text-3xl lg:text-4xl
                           font-black leading-tight">

                    OpenVidra Documentation
                </h1>

                <p class="text-slate-400
                          mt-3 sm:mt-4
                          text-sm sm:text-base
                          max-w-3xl">

                    Explore guides, modules, AI features,
                    premium bundles, APIs and deployment
                    instructions for OpenVidra School ERP.
                </p>

            </div>

        </section>

        <!-- Content -->
        <section class="px-4 sm:px-6 lg:px-8 pb-6 sm:pb-8 lg:pb-10">

            <div class="max-w-6xl mx-auto
                        bg-[#0b1120]
                        border border-white/5
                        rounded-[24px] sm:rounded-[32px]
                        shadow-2xl
                        p-4 sm:p-6 lg:p-8">

                @if($video)

                <div class="mb-8">

                    <div class="flex flex-col sm:flex-row
                                items-start sm:items-center
                                justify-between gap-3 mb-4">

                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white">
                                🎥 Demo Walkthrough
                            </h2>

                            <p class="text-slate-400 text-xs sm:text-sm">
                                Watch how this module works in OpenVidra
                            </p>
                        </div>

                        <span class="bg-purple-500/10
                                    border border-purple-500/20
                                    text-purple-300
                                    px-3 py-1.5 rounded-full text-xs sm:text-sm">

                            Interactive Demo
                        </span>
                    </div>

                    <div class="relative overflow-hidden
                                rounded-[20px] sm:rounded-[30px]
                                border border-purple-500/10
                                bg-slate-950 shadow-xl">

                        <video
                            autoplay
                            muted
                            loop
                            playsinline
                            controls
                            preload="metadata"
                            class="w-full
                                h-auto
                                max-h-[200px] sm:max-h-[300px] md:max-h-[400px] lg:max-h-[500px]
                                object-cover">

                            <source src="{{ $video }}"
                                    type="video/webm">

                            Your browser does not support video.

                        </video>

                    </div>

                </div>

                @endif

                <article class="markdown">
                    {!! $content !!}
                </article>

            </div>

        </section>

    </main>

</div>

<script>
    const sidebar =
        document.getElementById('sidebar');

    const overlay =
        document.getElementById('overlay');

    const menuBtn =
        document.getElementById('menuBtn');

    const closeSidebarBtn =
        document.getElementById('closeSidebarBtn');

    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuBtn.addEventListener('click', () => {
        sidebar.classList.contains('active')
            ? closeSidebar()
            : openSidebar();
    });

    closeSidebarBtn.addEventListener('click', closeSidebar);

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });

    let touchStartX = 0;
    document.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });

    document.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        const diff = touchStartX - touchEndX;

        if (diff > 50 && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });
</script>

</body>
</html>