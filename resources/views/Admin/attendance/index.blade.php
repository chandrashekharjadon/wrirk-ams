@extends('layouts.app')

@section('content')
<div class="dashboard-page d-flex">
    <main class="flex-fill">
        <div class="container-fluid px-4 py-5">

            <!-- Page Header -->
            <div class="text-start mb-4">
                <h2 class="fw-bold page-title">Users Attendance Report 📆</h2>
                <p class="text-light small opacity-75">See Users weekly and monthly attendance breakdown.</p>
            </div>

            <!-- Filter Form -->
            {{-- 🔍 Filter Form --}}
            <div class="card dashboard-card p-4 mb-4 no-hover">
                <form action="{{ route('admin.attendance.index') }}" method="GET" class="row g-3 align-items-end">
                    
                    {{-- 👤 User --}}
                    <div class="col-md-3">
                        <label for="user_id" class="form-label text-light fw-semibold">Select User</label>
                        <select name="user_id" id="user_id" class="form-select filter-input" required>
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 📅 Month --}}
                    <div class="col-md-3">
                        <label for="month" class="form-label text-light fw-semibold">Select Month</label>
                        <select name="month" id="month" class="form-select filter-input" required>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 🗓️ Year --}}
                    <div class="col-md-3">
                        <label for="year" class="form-label text-light fw-semibold">Select Year</label>
                        <select name="year" id="year" class="form-select filter-input" required>
                            @for($y = now()->year - 5; $y <= now()->year; $y++)
                                <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- 🔘 Actions --}}
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-premium me-2">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary reset-btn">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>


            <!-- Monthly Summary -->
            @if(!empty($month_summary))
            <div class="card dashboard-card p-4 mb-4">
                <h5 class="text-light fw-semibold mb-3">Monthly Summary</h5>
                <div class="row g-3">
                    @foreach([
                    'total_working_hours' => 'Working Hours',
                    'total_working_days' => 'Working Days',
                    'total_half_days' => 'Half Days',
                    'total_leaves' => 'Leaves',
                    'total_wfh' => 'WFH',
                    'total_sandwitch' => 'Sandwich'
                    ] as $key => $label)
                    <div class="col-6 col-md-2">
                        <div class="p-3 text-center bg-light bg-opacity-10 border rounded summary-box">
                            <strong class="d-block mb-1">{{ $label }}</strong>
                            <span class="fs-5 text-white">{{ $month_summary[$key] ?? 0 }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


            <!-- Weekly Breakdown -->
            @if(!empty($weeks) && count($weeks) > 0)
            @foreach($weeks as $index => $week)
            <div class="card dashboard-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-light fw-semibold mb-0">Week {{ $index + 1 }}</h5>
                    <small class="text-light opacity-75">
                        {{ \Carbon\Carbon::parse($week['week_days'][0]['date'])->format('d M') }} -
                        {{ \Carbon\Carbon::parse(end($week['week_days'])['date'])->format('d M') }}
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($week['week_days'] as $day)
                            @php
                            $parsedDate = \Carbon\Carbon::parse($day['date']);
                            $dayName = $parsedDate->format('l');
                            $status = $day['status'];
                            if ($dayName === 'Sunday') {
                            $status = 'sunday';
                            }
                            @endphp
                            <tr>
                                <td><strong>{{ $parsedDate->format('d M, Y') }}</strong></td>
                                <td>{{ $dayName }}</td>
                                <td>{{ $dayName === 'Sunday' ? '--' : ($day['check_in'] ?? '--') }}</td>
                                <td>{{ $dayName === 'Sunday' ? '--' : ($day['check_out'] ?? '--') }}</td>
                                <td>{{ number_format($day['hours'] ?? 0, 2) }}</td>
                                <td>
                                    @switch($status)
                                    @case('absent') <span class="status-badge status-danger">Leave</span> @break
                                    @case('wfh') <span class="status-badge status-secondary">WFH</span> @break
                                    @case('holiday') <span class="status-badge status-warning text-dark">Holiday</span>
                                    @break
                                    @case('sunday') <span class="status-badge status-warning text-dark">Sunday
                                        Off</span> @break
                                    @default <span class="status-badge status-success">Present</span>
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row text-center g-3 mt-3">
                    @foreach([
                    'working_hours' => 'Working Hours',
                    'working_days' => 'Working Days',
                    'half_days' => 'Half Days',
                    'leaves' => 'Leaves',
                    'wfh' => 'WFH',
                    'sandwitch' => 'Sandwich'
                    ] as $key => $label)
                    <div class="col-md-2 col-6">
                        <div class="p-3 bg-light bg-opacity-10 border rounded summary-box">
                            <strong>{{ $label }}</strong><br>
                            <span class="text-white">{{ $week['summary'][$key] ?? 0 }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            @elseif(request()->filled(['month','year']))
            <div class="card dashboard-card text-center p-5">
                <h5 class="text-light opacity-85 mb-2">⚠ No Attendance Data Found</h5>
                <p class="text-light opacity-75">No records available for this month.</p>
            </div>
            @endif

        </div>
    </main>
</div>

<!-- Styling -->
<style>
    .dashboard-page {
        min-height: 100vh;
        background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
        color: #fff;
        font-family: 'Poppins', sans-serif;
    }

    .page-title {
        color: #fff;
    }

    /* Card Style */
    .dashboard-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        backdrop-filter: blur(12px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover:not(.no-hover) {
        transform: translateY(-3px);
    }

    /* Filter input */
    .filter-input {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        border-radius: 10px;
    }

    .filter-input option {
        color: #000;
    }

    /* Buttons */
    .btn-premium {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        border: none;
        color: #fff;
        border-radius: 999px;
        padding: 8px 20px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 65, 108, 0.2);
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(255, 65, 108, 0.3);
    }

    .reset-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #fff;
    }

    .reset-btn:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Table */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table thead {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .custom-table th,
    .custom-table td {
        padding: 14px 16px;
    }

    .custom-table td {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.92);
    }

    .custom-table tr:hover td {
        background: rgba(255, 255, 255, 0.08);
        transition: all 0.3s ease;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-success {
        background: linear-gradient(135deg, #00b09b, #96c93d);
        color: #fff;
    }

    .status-warning {
        background: linear-gradient(135deg, #f7971e, #ffd200);
        color: #000;
    }

    .status-danger {
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        color: #fff;
    }

    .status-secondary {
        background: rgba(255, 255, 255, 0.3);
        color: #fff;
    }

    /* Summary Boxes */
    .summary-box {
        transition: all 0.3s ease;
    }

    .summary-box:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .summary-box {
        min-height: 90px;
        /* ensure consistent height */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
    }

    .summary-box strong {
        font-size: 0.85rem;
        color: #fff;
    }

    .summary-box span {
        font-size: 1.25rem;
        font-weight: 600;
        color: #fff;
    }
</style>
@endsection
