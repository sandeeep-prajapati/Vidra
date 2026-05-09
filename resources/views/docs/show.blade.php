<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

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

        /* Scrollbar */
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

        /* Markdown Styling */
        .markdown {
            color: #cbd5e1;
            line-height: 1.9;
            overflow-wrap: break-word;
        }

        .markdown h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding-bottom: 15px;
        }

        .markdown h2 {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #c084fc;
        }

        .markdown h3 {
            font-size: clamp(1.1rem, 3vw, 1.35rem);
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: .8rem;
            color: #67e8f9;
        }

        .markdown p {
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        .markdown strong {
            color: white;
        }

        .markdown ul,
        .markdown ol {
            margin-left: 25px;
            margin-bottom: 1rem;
        }

        .markdown ul {
            list-style: disc;
        }

        .markdown ol {
            list-style: decimal;
        }

        .markdown li {
            margin-bottom: 10px;
        }

        .markdown a {
            color: #a855f7;
        }

        .markdown a:hover {
            text-decoration: underline;
        }

        .markdown hr {
            border-color: rgba(255,255,255,.08);
            margin: 2rem 0;
        }

        .markdown table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            border-radius: 20px;
        }

        .markdown th {
            background: #111827;
            color: white;
            padding: 16px;
            text-align: left;
        }

        .markdown td {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,.05);
        }

        .markdown pre {
            background: #020617;
            border: 1px solid rgba(124,58,237,.2);
            border-radius: 24px;
            padding: 20px;
            overflow-x: auto;
            margin: 25px 0;
        }

        .markdown code {
            background: rgba(124,58,237,.12);
            color: #c084fc;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: .9rem;
        }

        .markdown pre code {
            background: transparent;
            padding: 0;
        }

        .markdown img {
            width: 100%;
            border-radius: 24px;
            margin: 25px 0;
            border: 1px solid rgba(255,255,255,.06);
            box-shadow: 0 15px 50px rgba(0,0,0,.4);
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
               w-[85%] sm:w-72 lg:w-80
               bg-[#080d1f]
               border-r border-purple-900/20
               flex flex-col
               transition-all duration-300
               sidebar-mobile
               lg:translate-x-0">

        <!-- Logo -->
        <div class="p-5 border-b border-purple-900/20">

            <a href="/" class="block">
                <h1 class="text-2xl lg:text-3xl font-black">
                    🚀
                    <span class="text-purple-400">
                        OpenVidra
                    </span>
                </h1>

                <p class="text-slate-400 mt-1 text-sm">
                    AI School ERP Docs
                </p>
            </a>

        </div>

        <!-- Sidebar Menu -->
        <div class="sidebar-scroll flex-1 overflow-y-auto p-4">

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

                <details open class="mb-4">

                    <summary
                        class="cursor-pointer rounded-2xl
                               px-5 py-4 font-semibold
                               bg-{{ $color }}-500/10
                               text-{{ $color }}-300
                               hover:bg-{{ $color }}-500/20
                               transition">

                        {{ Str::headline($section) }}
                    </summary>

                    <div class="mt-3 space-y-2 pl-3">

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
    <main class="flex-1 min-w-0 overflow-y-auto">

        <!-- Header -->
        <header class="sticky top-0 z-30
                       bg-[#050816]/90
                       backdrop-blur-xl
                       border-b border-white/5">

            <div class="px-4 sm:px-6 lg:px-8 py-4
                        flex flex-col sm:flex-row
                        items-start sm:items-center
                        justify-between gap-4">

                <div class="flex items-center gap-4">

                    <button id="menuBtn"
                        class="lg:hidden bg-slate-800
                               hover:bg-slate-700
                               p-3 rounded-xl transition">

                        ☰
                    </button>

                    <h2 class="text-2xl sm:text-3xl font-bold">
                        Documentation
                    </h2>

                </div>

                <div class="flex flex-wrap gap-3 w-full sm:w-auto">

                    <a href="/"
                       class="w-full sm:w-auto
                              text-center
                              bg-slate-800
                              hover:bg-slate-700
                              px-5 py-3 rounded-xl transition">

                        Website
                    </a>

                    <a href="/login"
                       class="w-full sm:w-auto
                              text-center
                              bg-gradient-to-r
                              from-purple-600
                              to-violet-500
                              hover:scale-105
                              transition
                              px-5 py-3
                              rounded-xl
                              font-semibold">

                        Open App →
                    </a>

                </div>

            </div>

        </header>

        <!-- Hero -->
        <section class="px-4 sm:px-6 lg:px-8 pt-6 lg:pt-10">

            <div class="bg-gradient-to-r
                        from-purple-700/20
                        to-cyan-600/10
                        border border-white/10
                        rounded-[32px]
                        p-6 sm:p-8 lg:p-10">

                <h1 class="text-3xl sm:text-4xl lg:text-5xl
                           font-black leading-tight">

                    OpenVidra Documentation
                </h1>

                <p class="text-slate-400
                          mt-4
                          text-base sm:text-lg
                          max-w-3xl">

                    Explore guides, modules,
                    AI features, premium bundles,
                    APIs and deployment instructions
                    for OpenVidra School ERP.
                </p>

            </div>

        </section>

        <!-- Content -->
        <section class="px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

            <div class="max-w-6xl mx-auto
                        bg-[#0b1120]
                        border border-white/5
                        rounded-[32px]
                        shadow-2xl
                        p-5 sm:p-8 lg:p-10">

                @if($video)

                <div class="mb-10">

                    <div class="flex flex-col sm:flex-row
                                items-start sm:items-center
                                justify-between gap-4 mb-5">

                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-white">
                                🎥 Demo Walkthrough
                            </h2>

                            <p class="text-slate-400 text-sm md:text-base">
                                Watch how this module works in OpenVidra
                            </p>
                        </div>

                        <span class="bg-purple-500/10
                                    border border-purple-500/20
                                    text-purple-300
                                    px-4 py-2 rounded-full text-sm">

                            Interactive Demo
                        </span>
                    </div>

                    <!-- Bigger Video -->
                    <div class="relative overflow-hidden
                                rounded-[30px]
                                border border-purple-500/10
                                bg-slate-950 shadow-2xl
                                w-full">

                        <video
                            autoplay
                            muted
                            loop
                            playsinline
                            controls
                            preload="auto"
                            class="w-full
                                h-[250px]
                                sm:h-[350px]
                                md:h-[450px]
                                lg:h-[550px]
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

    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    }

    menuBtn.addEventListener('click', () => {
        sidebar.classList.contains('active')
            ? closeSidebar()
            : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);
</script>

</body>
</html>