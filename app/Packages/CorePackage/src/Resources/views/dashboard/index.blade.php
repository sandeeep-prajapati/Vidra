@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Dashboard</span>
</nav>
@endsection

@section('content')

<style>
.dash-stats  { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1.25rem; margin-bottom:2rem; }
.dash-charts { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(380px,100%),1fr)); gap:1.5rem; margin-bottom:2rem; }
.dash-card   { background:#fff; border:1px solid #e2e8f0; border-radius:.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,.06); }
.dash-card h3 { font-size:1rem; font-weight:600; color:#1e293b; margin:0 0 1rem; }
.dash-stat-num { font-size:2rem; font-weight:700; color:#1e293b; margin:0; }
.dash-stat-label { font-size:.875rem; color:#64748b; margin:0 0 .5rem; }
.dash-stat-icon { border-radius:.5rem; padding:.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.dash-attend-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem; text-align:center; }

@media (max-width: 480px) {
    .dash-stats { grid-template-columns: 1fr 1fr; gap: .875rem; }
    .dash-stat-num { font-size: 1.5rem; }
    .dash-stat-icon { padding: .6rem; }
    .dash-card { padding: 1rem; }
    .dash-attend-grid { grid-template-columns: repeat(4,1fr); gap:.5rem; }
}
</style>

<div style="padding:1.25rem;max-width:100%;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:2rem;">
        <h1 style="font-size:1.875rem;font-weight:700;color:#1e293b;margin:0;">Dashboard</h1>
        <p style="font-size:.875rem;color:#64748b;margin:.5rem 0 0;">Welcome back! Here's an overview of your school</p>
    </div>

    <!-- Stats Cards -->
    <div class="dash-stats">
        <div class="dash-card">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p class="dash-stat-label">Total Students</p>
                    <p class="dash-stat-num">{{ $totalStudents }}</p>
                </div>
                <div class="dash-stat-icon" style="background:#ede9fe;color:#7c3aed;">
                    <svg style="width:1.75rem;height:1.75rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="dash-card">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p class="dash-stat-label">Total Staff</p>
                    <p class="dash-stat-num">{{ $totalStaff }}</p>
                </div>
                <div class="dash-stat-icon" style="background:#fce7f3;color:#ec4899;">
                    <svg style="width:1.75rem;height:1.75rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
        </div>

        <div class="dash-card">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p class="dash-stat-label">Total Classes</p>
                    <p class="dash-stat-num">{{ $totalClasses }}</p>
                </div>
                <div class="dash-stat-icon" style="background:#dcfce7;color:#16a34a;">
                    <svg style="width:1.75rem;height:1.75rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>

        <div class="dash-card">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p class="dash-stat-label">Total Fees</p>
                    <p class="dash-stat-num">₹{{ $totalFees }}</p>
                </div>
                <div class="dash-stat-icon" style="background:#fef3c7;color:#ca8a04;">
                    <svg style="width:1.75rem;height:1.75rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="dash-charts">
        <div class="dash-card">
            <h3>Student Growth (Last 6 Months)</h3>
            <canvas id="studentGrowthChart" style="max-height:280px;"></canvas>
        </div>

        <div class="dash-card">
            <h3>Attendance Rate</h3>
            <div class="dash-attend-grid">
                <div>
                    <p style="font-size:1.4rem;font-weight:700;color:#16a34a;margin:0;">{{ $attendanceRate['present'] }}</p>
                    <p style="font-size:.72rem;color:#64748b;margin:.25rem 0 0;">Present</p>
                </div>
                <div>
                    <p style="font-size:1.4rem;font-weight:700;color:#ef4444;margin:0;">{{ $attendanceRate['absent'] }}</p>
                    <p style="font-size:.72rem;color:#64748b;margin:.25rem 0 0;">Absent</p>
                </div>
                <div>
                    <p style="font-size:1.4rem;font-weight:700;color:#f59e0b;margin:0;">{{ $attendanceRate['late'] }}</p>
                    <p style="font-size:.72rem;color:#64748b;margin:.25rem 0 0;">Late</p>
                </div>
                <div>
                    <p style="font-size:1.4rem;font-weight:700;color:#0ea5e9;margin:0;">{{ $attendanceRate['total'] }}</p>
                    <p style="font-size:.72rem;color:#64748b;margin:.25rem 0 0;">Total</p>
                </div>
            </div>
            <canvas id="attendanceChart" style="max-height:220px;"></canvas>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="dash-charts">
        <div class="dash-card">
            <h3>Fee Collection (Last 6 Months)</h3>
            <canvas id="feeCollectionChart" style="max-height:280px;"></canvas>
        </div>

        <div class="dash-card">
            <h3>Staff Distribution</h3>
            <canvas id="staffDistributionChart" style="max-height:280px;"></canvas>
        </div>
    </div>

    <!-- Messages & Events -->
    <div class="dash-charts">
        <div class="dash-card" style="padding:0;overflow:hidden;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:.875rem 1.25rem;">
                <h3 style="margin:0;">Recent Messages</h3>
            </div>
            <div style="padding:1.25rem;">
                @if(count($recentMessages) > 0)
                    @foreach($recentMessages as $message)
                    <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9;">
                        <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0 0 .25rem;">{{ $message['title'] }}</p>
                        <p style="font-size:.75rem;color:#64748b;margin:0 0 .5rem;">{{ Str::limit($message['content'], 80) }}</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin:0;">{{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</p>
                    </div>
                    @endforeach
                @else
                    <p style="color:#94a3b8;text-align:center;padding:2rem 0;font-size:.85rem;">No messages yet</p>
                @endif
            </div>
        </div>

        <div class="dash-card" style="padding:0;overflow:hidden;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:.875rem 1.25rem;">
                <h3 style="margin:0;">Upcoming Events</h3>
            </div>
            <div style="padding:1.25rem;">
                @if(count($upcomingEvents) > 0)
                    @foreach($upcomingEvents as $event)
                    <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9;">
                        <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0 0 .25rem;">{{ $event['event_name'] }}</p>
                        <p style="font-size:.75rem;color:#64748b;margin:0 0 .5rem;">{{ Str::limit($event['description'] ?? '', 80) }}</p>
                        <p style="font-size:.72rem;color:#0ea5e9;font-weight:600;margin:0;">{{ \Carbon\Carbon::parse($event['event_date'])->format('M d, Y') }}</p>
                    </div>
                    @endforeach
                @else
                    <p style="color:#94a3b8;text-align:center;padding:2rem 0;font-size:.85rem;">No upcoming events</p>
                @endif
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Student Growth Chart
    const studentGrowthCtx = document.getElementById('studentGrowthChart');
    if (studentGrowthCtx) {
        new Chart(studentGrowthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($studentGrowth['labels']) !!},
                datasets: [{
                    label: 'New Students',
                    data: {!! json_encode($studentGrowth['data']) !!},
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0ea5e9'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx) {
        new Chart(attendanceCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late'],
                datasets: [{
                    data: [{{ $attendanceRate['present'] }}, {{ $attendanceRate['absent'] }}, {{ $attendanceRate['late'] }}],
                    backgroundColor: ['#16a34a', '#ef4444', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Fee Collection Chart
    const feeCtx = document.getElementById('feeCollectionChart');
    if (feeCtx) {
        new Chart(feeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($feeCollection['labels']) !!},
                datasets: [{
                    label: 'Fee Amount',
                    data: {!! json_encode($feeCollection['data']) !!},
                    backgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Staff Distribution Chart
    const staffCtx = document.getElementById('staffDistributionChart');
    if (staffCtx) {
        new Chart(staffCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($staffDistribution['labels']) !!},
                datasets: [{
                    data: {!! json_encode($staffDistribution['data']) !!},
                    backgroundColor: [
                        '#0ea5e9', '#f59e0b', '#ef4444', '#8b5cf6', '#10b981'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
</script>

@endsection
