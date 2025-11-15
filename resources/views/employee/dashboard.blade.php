@extends('layouts.employee')

@section('content')
<div class="dashboard-page d-flex">
    <!-- Main content area (assumes sidebar sits beside this in your layout) -->
    <main class="flex-fill">
        <div class="container-fluid px-4 py-5">

            <div class="text-start mb-4">
                <h2 class="fw-bold page-title">Welcome, {{ Auth::user()->name }} 👋</h2>
                <p class="text-light small opacity-75">Your performance summary</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
            @endif

            <!-- FIRST ROW: EXACTLY 4 CARDS ON LARGE SCREENS -->
            <div class="row g-4 mb-4 justify-content-start">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet mb-3">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Work From Home</h6>
                       
                        <a href="{{ route('employee.wfh') }}" class="btn btn-premium">View Details</a>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-light mb-3">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Attendance Days</h6>
                       
                        <a href="{{ route('employee.attendance') }}" class="btn btn-premium">View Details</a>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-green mb-3">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Remaining Leaves</h6>
                       
                        <a href="{{ route('employee.leaves') }}" class="btn btn-premium">Manage</a>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card dashboard-card h-100 text-center p-4">
                        <div class="icon-wrapper bg-gradient-violet-orange mb-3">
                            <i class="bi bi-sun-fill"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Upcoming Holidays</h6>
                        <!-- <h2 class="fw-bold mb-3">{{ $upcomingHolidays ?? 0 }}</h2> -->
                        <a href="{{ route('employee.holidays') }}" class="btn btn-premium">View</a>
                    </div>
                </div>
            </div>

            <!-- SECOND ROW: Single centered card (5th card) -->
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 px-0">
                        <div class="card dashboard-card text-center p-4 mx-auto">
                            <div class="icon-wrapper bg-gradient-violet-red mb-3">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <h6 class="fw-semibold mb-1">Salary Slip</h6>
                           
                            <a href="{{ route('employee.salaryslip') }}" class="btn btn-premium">View Slip</a>
                        </div>
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

/* Page title */
.page-title {
    color: #fff;
    margin-bottom: 0.25rem;
}

/* ---------- Cards ---------- */
.dashboard-card {
    background: rgba(255, 255, 255, 0.06); /* subtle glass */
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

/* Icon circle */
.icon-wrapper {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 26px;
    margin: 0 auto 12px;
    box-shadow: inset 0 -6px 18px rgba(255,255,255,0.02);
}

/* Gradients for icons (matching sidebar colors) */
.bg-gradient-violet { background: linear-gradient(135deg, #6a11cb, #2575fc); }
.bg-gradient-violet-light { background: linear-gradient(135deg, #7f00ff, #ad1cff); }
.bg-gradient-violet-green { background: linear-gradient(135deg, #36d1dc, #5b86e5); }
.bg-gradient-violet-orange { background: linear-gradient(135deg, #f7971e, #ffd200); }
.bg-gradient-violet-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }

/* Buttons */
.btn-premium {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none;
    color: #fff;
    border-radius: 999px;
    padding: 8px 18px;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(255,65,108,0.12);
    transition: transform .18s ease, box-shadow .18s ease;
}
.btn-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(255,65,108,0.18);
}

/* Text */
h6 { color: rgba(255,255,255,0.95); }
h2 { color: #fff; }



@media (min-width: 1200px) {
    /* xl: 4 columns (col-xl-3) already set */
}
@media (max-width: 991.98px) {
    /* On tablets and below, give cards good spacing */
    .dashboard-card { margin-bottom: 1rem; }
}
@media (max-width: 575.98px) {
    .icon-wrapper { width: 56px; height: 56px; font-size: 22px; }
    .btn-premium { padding: 6px 12px; }
}
</style>
@endsection
