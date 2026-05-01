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

            {{-- ── People ── --}}
            <button class="section-toggle" onclick="toggleSection('s-menu')">
                <span class="section-label">People</span>
                <svg id="s-menu-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-menu" class="section-items">
                <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Students
                </a>
                <a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Staff
                </a>
            </div>

            {{-- ── Academics ── --}}
            <button class="section-toggle" onclick="toggleSection('s-academics')">
                <span class="section-label">Academics</span>
                <svg id="s-academics-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-academics" class="section-items">
                <a href="{{ route('academic-years.index') }}" class="sidebar-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Academic Years
                </a>
                <a href="{{ route('classes.index') }}" class="sidebar-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Classes
                </a>
                <a href="{{ route('sections.index') }}" class="sidebar-link {{ request()->routeIs('sections.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Sections
                </a>
                <a href="{{ route('batches.index') }}" class="sidebar-link {{ request()->routeIs('batches.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Batches
                </a>
            </div>

            {{-- ── Subjects & Curriculum ── --}}
            <button class="section-toggle" onclick="toggleSection('s-subjects')">
                <span class="section-label">Subjects & Curriculum</span>
                <svg id="s-subjects-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-subjects" class="section-items">
                <a href="{{ route('subjects.index') }}" class="sidebar-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Subjects
                </a>
                <a href="{{ route('class-subjects.index') }}" class="sidebar-link {{ request()->routeIs('class-subjects.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Class-Subjects
                </a>
                <a href="{{ route('curriculums.index') }}" class="sidebar-link {{ request()->routeIs('curriculums.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Curriculum
                </a>
                <a href="{{ route('lesson-plans.index') }}" class="sidebar-link {{ request()->routeIs('lesson-plans.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Lesson Plans
                </a>
                <a href="{{ route('textbooks.index') }}" class="sidebar-link {{ request()->routeIs('textbooks.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Textbooks
                </a>
                <a href="{{ route('teacher-subject-mappings.index') }}" class="sidebar-link {{ request()->routeIs('teacher-subject-mappings.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Teacher Assignments
                </a>
            </div>

            {{-- ── Attendance ── --}}
            <button class="section-toggle" onclick="toggleSection('s-attendance')">
                <span class="section-label">Attendance</span>
                <svg id="s-attendance-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-attendance" class="section-items">
                <a href="{{ route('attendance.overview') }}" class="sidebar-link {{ request()->routeIs('attendance.overview') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Overview
                </a>
                <a href="{{ route('studentAttendance.index') }}" class="sidebar-link {{ request()->routeIs('studentAttendance.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Student Attendance
                </a>
                <a href="{{ route('teacherAttendance.index') }}" class="sidebar-link {{ request()->routeIs('teacherAttendance.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Teacher Attendance
                </a>
                <a href="{{ route('studentLeaveRequest.index') }}" class="sidebar-link {{ request()->routeIs('studentLeaveRequest.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Student Leave
                </a>
                <a href="{{ route('teacherLeaveRequest.index') }}" class="sidebar-link {{ request()->routeIs('teacherLeaveRequest.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Teacher Leave
                </a>
                <a href="{{ route('holiday.index') }}" class="sidebar-link {{ request()->routeIs('holiday.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Holidays
                </a>
            </div>

            {{-- ── Finance ── --}}
            <button class="section-toggle" onclick="toggleSection('s-finance')">
                <span class="section-label">Finance</span>
                <svg id="s-finance-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-finance" class="section-items">
                <a href="{{ route('feeCategory.index') }}" class="sidebar-link {{ request()->routeIs('feeCategory.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                    </svg>
                    Fee Categories
                </a>
                <a href="{{ route('feeStructure.index') }}" class="sidebar-link {{ request()->routeIs('feeStructure.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Fee Structures
                </a>
                <a href="{{ route('studentFee.index') }}" class="sidebar-link {{ request()->routeIs('studentFee.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Student Fees
                </a>
                <a href="{{ route('feePayment.index') }}" class="sidebar-link {{ request()->routeIs('feePayment.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Payments
                </a>
                <a href="{{ route('discount.index') }}" class="sidebar-link {{ request()->routeIs('discount.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Discounts
                </a>
                <a href="{{ route('studentDiscount.index') }}" class="sidebar-link {{ request()->routeIs('studentDiscount.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Student Discounts
                </a>
                <a href="{{ route('expense.index') }}" class="sidebar-link {{ request()->routeIs('expense.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Expenses
                </a>
                <a href="{{ route('financialReport.index') }}" class="sidebar-link {{ request()->routeIs('financialReport.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Financial Reports
                </a>
            </div>

            {{-- ── Timetable ── --}}
            <button class="section-toggle" onclick="toggleSection('s-timetable')">
                <span class="section-label">Timetable</span>
                <svg id="s-timetable-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-timetable" class="section-items">
                <a href="{{ route('timetable.index') }}" class="sidebar-link {{ request()->routeIs('timetable.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Timetable
                </a>
                <a href="{{ route('period.index') }}" class="sidebar-link {{ request()->routeIs('period.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Periods
                </a>
                <a href="{{ route('day.index') }}" class="sidebar-link {{ request()->routeIs('day.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/>
                    </svg>
                    Days
                </a>
                <a href="{{ route('room.index') }}" class="sidebar-link {{ request()->routeIs('room.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Rooms
                </a>
                <a href="{{ route('substituteAssignment.index') }}" class="sidebar-link {{ request()->routeIs('substituteAssignment.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Substitutions
                </a>
                <a href="{{ route('specialEvent.index') }}" class="sidebar-link {{ request()->routeIs('specialEvent.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Special Events
                </a>
            </div>

            {{-- ── Communication ── --}}
            <button class="section-toggle" onclick="toggleSection('s-communication')">
                <span class="section-label">Communication</span>
                <svg id="s-communication-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-communication" class="section-items">
                <a href="{{ route('message.index') }}" class="sidebar-link {{ request()->routeIs('message.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Messages
                </a>
                <a href="{{ route('messageRecipient.index') }}" class="sidebar-link {{ request()->routeIs('messageRecipient.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Recipients
                </a>
                <a href="{{ route('circular.index') }}" class="sidebar-link {{ request()->routeIs('circular.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Circulars
                </a>
                <a href="{{ route('notificationSetting.index') }}" class="sidebar-link {{ request()->routeIs('notificationSetting.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notification Prefs
                </a>
            </div>

            {{-- ── Hostel, Transport & Facilities ── --}}
            <button class="section-toggle" onclick="toggleSection('s-hostel')">
                <span class="section-label">Hostel & Transport</span>
                <svg id="s-hostel-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-hostel" class="section-items">
                <a href="{{ route('hostels.index') }}" class="sidebar-link {{ request()->routeIs('hostels.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Hostels
                </a>
                <a href="{{ route('hostel-rooms.index') }}" class="sidebar-link {{ request()->routeIs('hostel-rooms.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Hostel Rooms
                </a>
                <a href="{{ route('student-hostels.index') }}" class="sidebar-link {{ request()->routeIs('student-hostels.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Hostel Assignments
                </a>
                <a href="{{ route('transportation.index') }}" class="sidebar-link {{ request()->routeIs('transportation.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17h8m-8 0H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-2m-8 0v2a2 2 0 002 2h4a2 2 0 002-2v-2"/>
                    </svg>
                    Transportation
                </a>
                <a href="{{ route('student-transport.index') }}" class="sidebar-link {{ request()->routeIs('student-transport.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Transport Assignments
                </a>
                <a href="{{ route('facilities.index') }}" class="sidebar-link {{ request()->routeIs('facilities.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    Facilities
                </a>
                <a href="{{ route('facility-bookings.index') }}" class="sidebar-link {{ request()->routeIs('facility-bookings.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Facility Bookings
                </a>
            </div>

            {{-- ── Examinations ── --}}
            <button class="section-toggle" onclick="toggleSection('s-exams')">
                <span class="section-label">Examinations</span>
                <svg id="s-exams-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-exams" class="section-items">
                <a href="{{ route('exam.index') }}" class="sidebar-link {{ request()->routeIs('exam.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Exams
                </a>
                <a href="{{ route('examSchedule.index') }}" class="sidebar-link {{ request()->routeIs('examSchedule.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Exam Schedules
                </a>
                <a href="{{ route('gradingScheme.index') }}" class="sidebar-link {{ request()->routeIs('gradingScheme.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Grading Schemes
                </a>
                <a href="{{ route('studentMark.index') }}" class="sidebar-link {{ request()->routeIs('studentMark.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Student Marks
                </a>
                <a href="{{ route('studentReportCard.index') }}" class="sidebar-link {{ request()->routeIs('studentReportCard.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Report Cards
                </a>
            </div>

            {{-- ── Data Transfer ── --}}
            <button class="section-toggle" onclick="toggleSection('s-datatransfer')">
                <span class="section-label">Data Transfer</span>
                <svg id="s-datatransfer-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-datatransfer" class="section-items">
                <a href="{{ route('data-transfer.index') }}" class="sidebar-link {{ request()->routeIs('data-transfer.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import / Export
                </a>
            </div>

            {{-- ── Administration (RBAC) ── --}}
            <button class="section-toggle" onclick="toggleSection('s-rbac')">
                <span class="section-label">Administration</span>
                <svg id="s-rbac-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-rbac" class="section-items">
                <a href="{{ route('roles.index') }}" class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Roles
                </a>
                <a href="{{ route('permissions.index') }}" class="sidebar-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Permissions
                </a>
                <a href="{{ route('role-permissions.index') }}" class="sidebar-link {{ request()->routeIs('role-permissions.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm12 0a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    Role Permissions
                </a>
                <a href="{{ route('user-roles.index') }}" class="sidebar-link {{ request()->routeIs('user-roles.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    User Roles
                </a>
            </div>

            {{-- ── System (Webhook) ── --}}
            <button class="section-toggle" onclick="toggleSection('s-system')">
                <span class="section-label">System</span>
                <svg id="s-system-ch" class="section-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="s-system" class="section-items">
                <a href="{{ route('webhook.settings.index') }}" class="sidebar-link {{ request()->routeIs('webhook.settings.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Webhook Settings
                </a>
                <a href="{{ route('webhook.logs.index') }}" class="sidebar-link {{ request()->routeIs('webhook.logs.*') ? 'active' : '' }}">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Webhook Logs
                </a>
            </div>

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
const SB_SECTIONS = ['s-menu', 's-academics', 's-subjects', 's-attendance', 's-finance', 's-timetable', 's-communication', 's-hostel', 's-exams', 's-datatransfer', 's-rbac', 's-system'];

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
