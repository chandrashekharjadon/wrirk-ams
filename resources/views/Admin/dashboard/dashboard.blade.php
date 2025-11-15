@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="dashboard-page d-flex">
    <main class="flex-fill">
        <div class="container-fluid px-4 py-5">

            <div class="text-start mb-4">
                <h2 class="fw-bold page-title">Welcome, {{ Auth::user()->name }} 👋</h2>
                <p class="text-light small opacity-75">Organization Overview</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
            @endif

            <!-- ROW: DASHBOARD CARDS -->
            <div class="row g-4 mb-4 justify-content-start">

                <!-- Total Employees -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet mb-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Total Employees</h6>
                        <h2 class="fw-bold mb-3">{{ $totalEmployees ?? 0 }}</h2>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-premium">Manage</a>
                    </div>
                </div>

                <!-- Departments -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-green mb-3">
                            <i class="bi bi-buildings-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Departments</h6>
                        <h2 class="fw-bold mb-3">{{ $totalDepartments ?? 0 }}</h2>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-premium">View</a>
                    </div>
                </div>

                <!-- Designations -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-light mb-3">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Designations</h6>
                        <h2 class="fw-bold mb-3">{{ $totalDesignations ?? 0 }}</h2>
                        <a href="{{ route('admin.designations.index') }}" class="btn btn-premium">View</a>
                    </div>
                </div>

                <!-- Holidays -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-orange mb-3">
                            <i class="bi bi-calendar2-check-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Holidays</h6>
                        <h2 class="fw-bold mb-3">{{ $totalHolidays ?? 0 }}</h2>
                        <a href="{{ route('admin.holidays.index') }}" class="btn btn-premium">View</a>
                    </div>
                </div>

                <!-- Salary Slips -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-red mb-3">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Salary Slips</h6>
                        <a href="{{ route('admin.salaryslip.index') }}" class="btn btn-premium">View</a>
                    </div>
                </div>


                <!-- Attendance Reports -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-green mb-3">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Attendance Reports</h6>
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-premium">View</a>
                    </div>
                </div>

                <!-- Attendance Compare -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet mb-3">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Attendance Compare</h6>
                        <a href="{{ route('admin.compair.matching') }}" class="btn btn-premium">View</a>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<style>
.dashboard-page {
    min-height: 100vh;
    background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
    color: #fff;
}

.page-title { color: #fff; margin-bottom: 0.25rem; }

.dashboard-card {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    color: #fff;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.45);
    transition: transform .28s cubic-bezier(.22,.9,.3,1), box-shadow .28s;
}
.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(106, 17, 203, 0.45);
}

.icon-wrapper {
    width: 64px; height: 64px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    color: #fff; font-size: 26px; margin: 0 auto 12px;
    box-shadow: inset 0 -6px 18px rgba(255,255,255,0.02);
}

/* Gradients */
.bg-gradient-violet { background: linear-gradient(135deg, #6a11cb, #2575fc); }
.bg-gradient-violet-light { background: linear-gradient(135deg, #7f00ff, #ad1cff); }
.bg-gradient-violet-green { background: linear-gradient(135deg, #36d1dc, #5b86e5); }
.bg-gradient-violet-orange { background: linear-gradient(135deg, #f7971e, #ffd200); }
.bg-gradient-violet-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }

.btn-premium {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none; color: #fff; border-radius: 999px;
    padding: 8px 18px; font-weight: 600;
    box-shadow: 0 6px 18px rgba(255,65,108,0.12);
    transition: transform .18s ease, box-shadow .18s ease;
}
.btn-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(255,65,108,0.18);
}

h6 { color: rgba(255,255,255,0.95); }
h2 { color: #fff; }

@media (max-width: 991.98px) { .dashboard-card { margin-bottom: 1rem; } }
@media (max-width: 575.98px) {
    .icon-wrapper { width: 56px; height: 56px; font-size: 22px; }
    .btn-premium { padding: 6px 12px; }
}
</style>

@endsection
