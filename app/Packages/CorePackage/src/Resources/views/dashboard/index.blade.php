@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Dashboard</span>
</nav>
@endsection

@section('content')

<div style="padding:1.5rem;max-width:100%;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:2rem;">
        <h1 style="font-size:1.875rem;font-weight:700;color:#1e293b;margin:0;">Dashboard</h1>
        <p style="font-size:.875rem;color:#64748b;margin:.5rem 0 0;">Welcome back! Here's an overview of your school</p>
    </div>

    <!-- Stats Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <!-- Total Students -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:.875rem;color:#64748b;margin:0 0 .5rem;">Total Students</p>
                    <p style="font-size:2rem;font-weight:700;color:#1e293b;margin:0;">{{ $totalStudents }}</p>
                </div>
                <div style="background:#ede9fe;border-radius:.5rem;padding:1rem;color:#7c3aed;">
                    <svg style="width:2rem;height:2rem;" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-10-5v5m5-5v5M3.5 9.5h13"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total Staff -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:.875rem;color:#64748b;margin:0 0 .5rem;">Total Staff</p>
                    <p style="font-size:2rem;font-weight:700;color:#1e293b;margin:0;">{{ $totalStaff }}</p>
                </div>
                <div style="background:#fce7f3;border-radius:.5rem;padding:1rem;color:#ec4899;">
                    <svg style="width:2rem;height:2rem;" fill="currentColor" viewBox="0 0 20 20"><path d="M10 10a3 3 0 100-6 3 3 0 000 6zm0 1.5a6 6 0 00-5.933 4.743.75.75 0 00.768.936h11.33a.75.75 0 00.768-.936A6 6 0 0010 11.5z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total Classes -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:.875rem;color:#64748b;margin:0 0 .5rem;">Total Classes</p>
                    <p style="font-size:2rem;font-weight:700;color:#1e293b;margin:0;">{{ $totalClasses }}</p>
                </div>
                <div style="background:#dcfce7;border-radius:.5rem;padding:1rem;color:#16a34a;">
                    <svg style="width:2rem;height:2rem;" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total Fees -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:.875rem;color:#64748b;margin:0 0 .5rem;">Total Fees</p>
                    <p style="font-size:2rem;font-weight:700;color:#1e293b;margin:0;">${{ $totalFees }}</p>
                </div>
                <div style="background:#fef3c7;border-radius:.5rem;padding:1rem;color:#ca8a04;">
                    <svg style="width:2rem;height:2rem;" fill="currentColor" viewBox="0 0 20 20"><path d="M8.5 5a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM12.027 12.857a4 4 0 11-5.054 0"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <!-- Student Growth Chart -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin:0 0 1rem;">Student Growth (Last 6 Months)</h3>
            <canvas id="studentGrowthChart" style="max-height:300px;"></canvas>
        </div>

        <!-- Attendance Rate -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin:0 0 1rem;">Attendance Rate</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <div style="text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:#16a34a;margin:0;">{{ $attendanceRate['present'] }}</p>
                    <p style="font-size:.75rem;color:#64748b;margin:.5rem 0 0;">Present</p>
                </div>
                <div style="text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:#ef4444;margin:0;">{{ $attendanceRate['absent'] }}</p>
                    <p style="font-size:.75rem;color:#64748b;margin:.5rem 0 0;">Absent</p>
                </div>
                <div style="text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:#f59e0b;margin:0;">{{ $attendanceRate['late'] }}</p>
                    <p style="font-size:.75rem;color:#64748b;margin:.5rem 0 0;">Late</p>
                </div>
                <div style="text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:#0ea5e9;margin:0;">{{ $attendanceRate['total'] }}</p>
                    <p style="font-size:.75rem;color:#64748b;margin:.5rem 0 0;">Total</p>
                </div>
            </div>
            <div style="width:100%;height:200px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Fee Collection & Staff Distribution -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <!-- Fee Collection -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin:0 0 1rem;">Fee Collection (Last 6 Months)</h3>
            <canvas id="feeCollectionChart" style="max-height:300px;"></canvas>
        </div>

        <!-- Staff Distribution -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin:0 0 1rem;">Staff Distribution</h3>
            <canvas id="staffDistributionChart" style="max-height:300px;"></canvas>
        </div>
    </div>

    <!-- Latest Messages & Events -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:1.5rem;">
        <!-- Recent Messages -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin:0;">Recent Messages</h3>
            </div>
            <div style="padding:1.5rem;">
                @if(count($recentMessages) > 0)
                    @foreach($recentMessages as $message)
                    <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9;">
                        <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0 0 .25rem;">{{ $message['title'] }}</p>
                        <p style="font-size:.75rem;color:#64748b;margin:0 0 .5rem;">{{ Str::limit($message['content'], 60) }}</p>
                        <p style="font-size:.75rem;color:#94a3b8;margin:0;">{{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</p>
                    </div>
                    @endforeach
                @else
                    <p style="color:#64748b;text-align:center;margin:2rem 0;">No messages yet</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Events -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:1rem 1.5rem;">
                <h3 style="font-size:1rem;font-weight:600;color:#1e293b;margin:0;">Upcoming Events</h3>
            </div>
            <div style="padding:1.5rem;">
                @if(count($upcomingEvents) > 0)
                    @foreach($upcomingEvents as $event)
                    <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9;">
                        <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0 0 .25rem;">{{ $event['event_name'] }}</p>
                        <p style="font-size:.75rem;color:#64748b;margin:0 0 .5rem;">{{ Str::limit($event['description'] ?? '', 60) }}</p>
                        <p style="font-size:.75rem;color:#0ea5e9;font-weight:500;margin:0;">{{ \Carbon\Carbon::parse($event['event_date'])->format('M d, Y') }}</p>
                    </div>
                    @endforeach
                @else
                    <p style="color:#64748b;text-align:center;margin:2rem 0;">No upcoming events</p>
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
