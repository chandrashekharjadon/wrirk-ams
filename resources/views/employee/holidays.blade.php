@extends('layouts.employee')

@section('content')
<div class="dashboard-page d-flex">
    <main class="flex-fill">
        <div class="container-fluid px-4 py-5">

            <!-- Page Title -->
            <div class="text-start mb-4">
                <h2 class="fw-bold page-title">🎉 Holiday Calendar</h2>
                <p class="text-light small opacity-75">Check upcoming company holidays and plan ahead</p>
            </div>

            {{-- Filter Section --}}
            <div class="card dashboard-card no-hover mb-4 p-4">
                <form method="GET" action="{{ route('employee.holidays') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="month" class="form-label fw-semibold text-light">Month</label>
                        <select name="month" id="month" class="form-select custom-select">
                            <option value="">All Months</option>
                            @foreach(range(1,12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="year" class="form-label fw-semibold text-light">Year</label>
                        <select name="year" id="year" class="form-select custom-select">
                            <option value="">--</option>
                            @foreach(range(now()->year - 1, now()->year + 2) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-premium">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('employee.holidays') }}" class="btn btn-outline-light rounded-pill px-3">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Holiday Cards --}}
            <div class="row g-4">
                @forelse($holidays as $holiday)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="card dashboard-card text-center p-4">
                            <div class="icon-wrapper bg-gradient-violet-orange mb-3">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <h6 class="fw-semibold mb-1">{{ $holiday->name }}</h6>
                            <h5 class="fw-bold mb-1 text-white">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('d M, Y') }}
                            </h5>
                            <span class="badge bg-gradient-violet-green px-3 py-2 rounded-pill">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('l') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-light opacity-75">No holidays found{{ $month || $year ? ' for this filter' : '.' }}</h5>
                    </div>
                @endforelse
            </div>

        </div>
    </main>
</div>

<style>
/* Main dashboard styling */
.dashboard-page {
    min-height: 100vh;
    background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
    color: #fff;
}

.page-title {
    color: #fff;
    margin-bottom: 0.25rem;
}

/* Filter form elements */
.custom-select {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 10px;
}
.custom-select:focus {
    border-color: #6a11cb;
    box-shadow: 0 0 6px rgba(106,17,203,0.4);
    background: rgba(255, 255, 255, 0.1);
}
.custom-select option {
    color: #000;
}

/* Cards */
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

/* ⛔ No hover for filter box */
.no-hover:hover {
    transform: none !important;
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.45) !important;
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

/* Gradients */
.bg-gradient-violet-orange { background: linear-gradient(135deg, #f7971e, #ffd200); }
.bg-gradient-violet-green { background: linear-gradient(135deg, #36d1dc, #5b86e5); }

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
.btn-outline-light {
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
}
.btn-outline-light:hover {
    background: rgba(255,255,255,0.15);
}

/* Responsive */
@media (max-width: 575.98px) {
    .icon-wrapper { width: 56px; height: 56px; font-size: 22px; }
    .btn-premium { padding: 6px 12px; }
}
</style>
@endsection
