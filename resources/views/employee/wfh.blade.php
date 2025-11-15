@extends('layouts.employee')

@section('content')
<div class="dashboard-page d-flex">
    <main class="flex-fill">
        <div class="container-fluid px-4 py-5">

            <!-- Page Title -->
            <div class="text-start mb-4">
                <h2 class="fw-bold page-title">Work From Home Records 🏠</h2>
                <p class="text-light small opacity-75">View your work-from-home requests and logs.</p>
            </div>

            <!-- Filter Section -->
            <div class="card dashboard-card p-4 mb-4">
                <form method="GET" action="{{ route('employee.wfh') }}" class="row g-3 align-items-end">
                   <div class="col-md-4">
                        <label for="month" class="form-label text-light opacity-85 fw-semibold">Select Month</label>
                        <input type="month" name="month" id="month" class="form-control filter-input" value="{{ $month }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-premium me-2">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('employee.leaves') }}" class="btn btn-secondary reset-btn">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Show Table Only if Date Selected -->
            @if($month)
            <div class="card dashboard-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Percent</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Remark</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wfhs as $wfh)
                                @php
                                    $statusColor = match($wfh->status) {
                                        'Approved' => 'success',
                                        'Pending' => 'warning',
                                        'Rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($wfh->date)->format('d M, Y') }}</strong></td>
                                    <td>{{ $wfh->percent ?? 0 }}%</td>
                                    <td>{{ $wfh->check_in ?? '--' }}</td>
                                    <td>{{ $wfh->check_out ?? '--' }}</td>
                                    <td>{{ $wfh->remark ?? '--' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($statusColor) }}">
                                            {{ $wfh->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-light opacity-75">
                                        No WFH records found for {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="card dashboard-card text-center p-5">
                <h5 class="text-light opacity-85 mb-2">📅 Please select a date</h5>
                <p class="text-light opacity-75">Use the filter above to view your WFH records.</p>
            </div>
            @endif

        </div>
    </main>
</div>

<style>
/* Background */
.dashboard-page {
    min-height: 100vh;
    background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
    color: #fff;
    font-family: 'Poppins', sans-serif;
}

/* Title */
.page-title {
    color: #fff;
}

/* Dashboard card style */
.dashboard-card {
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    color: #fff;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.4);
}

/* Table styling */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}
.custom-table thead {
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-weight: 600;
}
.custom-table th {
    padding: 14px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    text-transform: uppercase;
    font-size: 0.9rem;
}
.custom-table td {
    padding: 14px 16px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    color: rgba(255,255,255,0.9);
}
.custom-table tr:hover td {
    background: rgba(255,255,255,0.08);
    transition: all 0.3s ease;
}

/* Buttons */
.btn-premium {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none;
    color: #fff;
    border-radius: 999px;
    padding: 8px 18px;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(255,65,108,0.12);
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255,65,108,0.2);
}
.reset-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
}
.reset-btn:hover {
    background: rgba(255,255,255,0.3);
}

/* Status badges */
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
    background: rgba(255,255,255,0.2);
    color: #fff;
}
</style>
@endsection
