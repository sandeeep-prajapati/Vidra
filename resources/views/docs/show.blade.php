<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OpenVidra Docs</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: #050816;
            color: white;
            overflow: hidden;
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

        /* Sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        /* Markdown Styling */
        .markdown {
            color: #cbd5e1;
            line-height: 1.9;
        }

        .markdown h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding-bottom: 15px;
        }

        .markdown h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #c084fc;
        }

        .markdown h3 {
            font-size: 1.35rem;
            font-weight: 600;
            margin-top: 1.8rem;
            margin-bottom: .8rem;
            color: #67e8f9;
        }

        .markdown p {
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        .markdown strong {
            color: white;
            font-weight: 700;
        }

        .markdown ul {
            list-style: disc;
            margin-left: 25px;
            margin-bottom: 1rem;
        }

        .markdown ol {
            list-style: decimal;
            margin-left: 25px;
            margin-bottom: 1rem;
        }

        .markdown li {
            margin-bottom: 10px;
        }

        .markdown a {
            color: #a855f7;
            text-decoration: none;
        }

        .markdown a:hover {
            text-decoration: underline;
        }

        .markdown hr {
            border-color: rgba(255,255,255,.08);
            margin: 2rem 0;
        }

        .markdown table {
            width: 100%;
            margin: 2rem 0;
            border-collapse: collapse;
            overflow: hidden;
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
            color: #cbd5e1;
        }

        .markdown pre {
            background: #020617;
            border: 1px solid rgba(124,58,237,.2);
            border-radius: 24px;
            padding: 22px;
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

        @media(max-width:1024px){
            body{
                overflow:auto;
            }
        }
    </style>
</head>
<body>

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed lg:relative z-50 lg:translate-x-0
                  -translate-x-full transition duration-300
                  w-80 bg-[#080d1f]
                  border-r border-purple-900/20
                  h-screen flex flex-col">

        <!-- Logo -->
        <div class="p-6 border-b border-purple-900/20">

            <a href="/" class="block">
                <h1 class="text-3xl font-black">
                    🚀 <span class="text-purple-400">
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
    <main class="flex-1 overflow-y-auto">

        <!-- Topbar -->
        <header class="sticky top-0 z-40
                       bg-[#050816]/80
                       backdrop-blur-xl
                       border-b border-white/5">

            <div class="px-8 py-5 flex items-center justify-between">

                <div class="flex items-center gap-4">

                    <button onclick="toggleSidebar()"
                            class="lg:hidden bg-slate-800 p-3 rounded-xl">
                        ☰
                    </button>

                    <h2 class="text-3xl font-bold">
                        Documentation
                    </h2>

                </div>

                <div class="flex gap-3">

                    <a href="/"
                       class="bg-slate-800 hover:bg-slate-700
                              px-5 py-3 rounded-xl transition">

                        Website
                    </a>

                    <a href="/login"
                       class="bg-gradient-to-r
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
        <section class="px-8 pt-10">

            <div class="bg-gradient-to-r
                        from-purple-700/20
                        to-cyan-600/10
                        border border-white/10
                        rounded-[32px]
                        p-10">

                <h1 class="text-5xl font-black leading-tight">
                    OpenVidra Documentation
                </h1>

                <p class="text-slate-400 mt-4 text-lg max-w-3xl">
                    Explore guides, modules, AI features,
                    premium bundles, APIs and deployment
                    instructions for OpenVidra School ERP.
                </p>

            </div>

        </section>

        <!-- Markdown Content -->
        <section class="px-8 py-10">

            <div class="max-w-6xl mx-auto
                        bg-[#0b1120]
                        border border-white/5
                        rounded-[32px]
                        shadow-2xl
                        p-10">

                @if($video)

                    <div class="mb-10">

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-white">
                                    🎥 Demo Walkthrough
                                </h2>

                                <p class="text-slate-400">
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

                        <div class="relative overflow-hidden rounded-[28px]
                                    border border-purple-500/10
                                    bg-slate-950 shadow-2xl">

                            <video controls
                                preload="metadata"
                                class="w-full rounded-[28px]"
                                poster="/demo/video-poster.png">

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
    function toggleSidebar() {
        const sidebar =
            document.getElementById('sidebar');

        sidebar.classList.toggle('-translate-x-full');
    }
</script>

</body>
</html>