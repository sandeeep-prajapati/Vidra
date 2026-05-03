<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us - {{ config('app.name', 'School ERP') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen">

    <!-- Header -->
    <header class="w-full px-6 lg:px-8 pt-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-[#4f46e5] to-[#6366f1] rounded-[0.625rem] shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ config('app.name', 'School ERP') }}</p>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Get in touch</p>
                    </div>
                </a>
                <div class="flex items-center gap-4">
                    <nav class="hidden sm:flex items-center gap-6">
                        <a href="{{ url('/') }}" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">
                            Home
                        </a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">
                                Dashboard
                            </a>
                        @endauth
                        <a href="https://github.com/sandeeep-prajapati/Vidra" target="_blank" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">
                            GitHub
                        </a>
                    </nav>
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-4 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-xs leading-normal transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-4 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-xs leading-normal transition">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-6 lg:px-8 py-12">
        <div class="w-full max-w-6xl">

            <!-- Hero Section with Two Column Layout -->
            <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-12 mb-20">

                <!-- Left Column - Contact Form & Info -->
                <div class="flex-1 text-[13px] leading-[20px] p-6 pb-8 lg:p-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-lg">

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md text-green-700 dark:text-green-300 text-sm">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Let's work together</h1>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-8">Have a question or want to discuss premium packages? We'd love to hear from you.</p>

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2 uppercase tracking-wider">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                placeholder="Your name"
                                class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:border-[#4f46e5] dark:focus:border-[#6366f1] focus:outline-none transition text-sm @error('name') border-red-500 @enderror"
                                value="{{ old('name') }}"
                            >
                            @error('name')<p class="text-red-600 dark:text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2 uppercase tracking-wider">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                placeholder="your@email.com"
                                class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:border-[#4f46e5] dark:focus:border-[#6366f1] focus:outline-none transition text-sm @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}"
                            >
                            @error('email')<p class="text-red-600 dark:text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2 uppercase tracking-wider">Subject</label>
                            <select
                                id="subject"
                                name="subject"
                                required
                                class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#4f46e5] dark:focus:border-[#6366f1] focus:outline-none transition text-sm @error('subject') border-red-500 @enderror"
                            >
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" @selected(old('subject') == 'General Inquiry')>General Inquiry</option>
                                <option value="Premium Package" @selected(old('subject') == 'Premium Package')>Premium Package Interest</option>
                                <option value="Bug Report" @selected(old('subject') == 'Bug Report')>Bug Report</option>
                                <option value="Custom Development" @selected(old('subject') == 'Custom Development')>Custom Development</option>
                                <option value="Deployment Support" @selected(old('subject') == 'Deployment Support')>Deployment Support</option>
                            </select>
                            @error('subject')<p class="text-red-600 dark:text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2 uppercase tracking-wider">Message</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                required
                                placeholder="Tell us more about your inquiry..."
                                class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:border-[#4f46e5] dark:focus:border-[#6366f1] focus:outline-none transition text-sm resize-none @error('message') border-red-500 @enderror"
                            >{{ old('message') }}</textarea>
                            @error('message')<p class="text-red-600 dark:text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full px-5 py-2.5 bg-[#1b1b18] dark:bg-[#EDEDEC] text-[#FDFDFC] dark:text-[#1b1b18] font-medium rounded-sm hover:opacity-90 dark:hover:opacity-90 transition text-sm leading-normal"
                        >
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Right Column - About & Features -->
                <div class="flex-1 flex flex-col gap-8">

                    <!-- Profile Card -->
                    <div class="text-center lg:text-left">
                        <img
                            src="/storage/info/profile.jpeg"
                            alt="Sandeep Prajapati"
                            class="w-32 h-32 rounded-full mx-auto lg:mx-0 mb-6 border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0px_2px_8px_rgba(26,26,0,0.1)] dark:shadow-[0px_2px_8px_rgba(0,0,0,0.3)]"
                        >
                        <h2 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Sandeep Prajapati</h2>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-4 leading-relaxed">
                            Full-stack developer crafting comprehensive school management solutions with modern technologies.
                        </p>
                        <div class="flex flex-col gap-2">
                            <a
                                href="https://github.com/sandeeep-prajapati"
                                target="_blank"
                                class="inline-flex items-center justify-center lg:justify-start gap-2 px-4 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-xs font-medium transition"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v 3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                GitHub Profile
                            </a>
                            <a
                                href="https://github.com/sandeeep-prajapati/Vidra"
                                target="_blank"
                                class="inline-flex items-center justify-center lg:justify-start gap-2 px-4 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm text-xs font-medium transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4m0 6L9 9"/></svg>
                                Open Source Repo
                            </a>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4">
                        <p class="text-xs font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Why contact us?</p>
                        <div class="space-y-3">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#4f46e5]/10 dark:bg-[#6366f1]/10 flex items-center justify-center mt-0.5">
                                    <svg class="w-3 h-3 text-[#4f46e5] dark:text-[#6366f1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Premium Packages</p>
                                    <p class="text-[12px] text-[#706f6c] dark:text-[#A1A09A] mt-0.5">Ask about AI, Blockchain & IoT add-ons</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#4f46e5]/10 dark:bg-[#6366f1]/10 flex items-center justify-center mt-0.5">
                                    <svg class="w-3 h-3 text-[#4f46e5] dark:text-[#6366f1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Custom Development</p>
                                    <p class="text-[12px] text-[#706f6c] dark:text-[#A1A09A] mt-0.5">Request custom features for your school</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#4f46e5]/10 dark:bg-[#6366f1]/10 flex items-center justify-center mt-0.5">
                                    <svg class="w-3 h-3 text-[#4f46e5] dark:text-[#6366f1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Support & Deployment</p>
                                    <p class="text-[12px] text-[#706f6c] dark:text-[#A1A09A] mt-0.5">Get help with setup and deployment</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#4f46e5]/10 dark:bg-[#6366f1]/10 flex items-center justify-center mt-0.5">
                                    <svg class="w-3 h-3 text-[#4f46e5] dark:text-[#6366f1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Report Issues</p>
                                    <p class="text-[12px] text-[#706f6c] dark:text-[#A1A09A] mt-0.5">Found a bug? Let us know directly</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQs Section -->
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider mb-6">Frequently Asked Questions</p>
                </div>

                <div class="space-y-3">
                    <div class="text-[13px] leading-[20px] p-5 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm overflow-hidden">
                        <button class="faq-toggle w-full text-left flex justify-between items-center hover:opacity-70 transition" onclick="toggleFaq(this)">
                            <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Is the free version really free forever?</span>
                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A] transform transition" style="transform: rotate(0deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </button>
                        <div class="faq-content hidden pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-4 text-[#706f6c] dark:text-[#A1A09A]">
                            Yes! The free version includes all core features and will remain free forever. It's 100% open source and can be self-hosted on your own infrastructure.
                        </div>
                    </div>

                    <div class="text-[13px] leading-[20px] p-5 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm overflow-hidden">
                        <button class="faq-toggle w-full text-left flex justify-between items-center hover:opacity-70 transition" onclick="toggleFaq(this)">
                            <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">When will premium packages be available?</span>
                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A] transform transition" style="transform: rotate(0deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </button>
                        <div class="faq-content hidden pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-4 text-[#706f6c] dark:text-[#A1A09A]">
                            Premium packages are currently in development. We're aiming for Q3 2026 launch. Sign up with your email to be notified when they become available.
                        </div>
                    </div>

                    <div class="text-[13px] leading-[20px] p-5 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm overflow-hidden">
                        <button class="faq-toggle w-full text-left flex justify-between items-center hover:opacity-70 transition" onclick="toggleFaq(this)">
                            <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Do you offer support for the open source version?</span>
                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A] transform transition" style="transform: rotate(0deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </button>
                        <div class="faq-content hidden pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-4 text-[#706f6c] dark:text-[#A1A09A]">
                            Yes! Community support is available through GitHub issues and discussions. For priority support and guaranteed response times, check out our premium packages.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] mt-20">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xs font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider mb-4">About</h3>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                        School ERP is a comprehensive, open-source school management system built to simplify school operations.
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider mb-4">Navigation</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">Home</a></li>
                        @auth
                            <li><a href="{{ url('/dashboard') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">Login</a></li>
                        @endauth
                        <li><a href="https://github.com/sandeeep-prajapati/Vidra" target="_blank" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">Open Source Repo</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider mb-4">Connect</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="https://github.com/sandeeep-prajapati" target="_blank" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">GitHub Profile</a></li>
                        <li><a href="#" onclick="document.querySelector('input[name=email]').focus()" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition">Send a Message</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-8 text-center text-xs text-[#706f6c] dark:text-[#A1A09A]">
                <p>&copy; 2026 School ERP. Built by Sandeep Prajapati. Open source & free forever.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleFaq(button) {
            const content = button.nextElementSibling;
            const svg = button.querySelector('svg');
            content.classList.toggle('hidden');
            svg.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    </script>

</body>
</html>
