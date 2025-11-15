<div class="sidebar d-flex flex-column p-3 shadow-lg" 
     style="min-height: 100vh; width: 260px; background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%); color: #fff; border-top-right-radius: 00px; border-bottom-right-radius: 24px;">

    <!-- User Avatar & Name -->
    <div class="text-center mb-4">
        @php
            $userName = auth()->user()->name;
            $avatarText = collect(explode(' ', $userName))
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <div class="logo-avatar mx-auto d-flex align-items-center justify-content-center rounded-circle shadow-lg mb-2">
            <span class="fw-bold fs-3">{{ $avatarText }}</span>
        </div>

        <h5 class="mt-2 fw-semibold text-uppercase" style="letter-spacing: 1px;">
            {{ $userName }}
        </h5>
    </div>

    <hr class="border-light opacity-25">

    <!-- Navigation Menu -->
    <ul class="nav flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="{{ route('dashboard') }}" 
               class="nav-link d-flex align-items-center p-2 rounded-3 fw-medium {{ request()->routeIs('dashboard') ? 'active-link' : 'text-white link-hover' }}">
                <i class="bi bi-speedometer2 me-3 fs-5"></i> Dashboard 
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('employee.attendance') }}" 
               class="nav-link d-flex align-items-center p-2 rounded-3 fw-medium {{ request()->routeIs('employee.attendance') ? 'active-link' : 'text-white link-hover' }}">
                <i class="bi bi-calendar-check me-3 fs-5"></i> Attendance
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('employee.leaves') }}" 
               class="nav-link d-flex align-items-center p-2 rounded-3 fw-medium {{ request()->routeIs('employee.leaves') ? 'active-link' : 'text-white link-hover' }}">
                <i class="bi bi-person-badge me-3 fs-5"></i> Leaves
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('employee.holidays') }}" 
               class="nav-link d-flex align-items-center p-2 rounded-3 fw-medium {{ request()->routeIs('employee.holidays') ? 'active-link' : 'text-white link-hover' }}">
                <i class="bi bi-briefcase me-3 fs-5"></i> Holidays
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('employee.wfh') }}" 
               class="nav-link d-flex align-items-center p-2 rounded-3 fw-medium {{ request()->routeIs('employee.wfh') ? 'active-link' : 'text-white link-hover' }}">
                <i class="bi bi-house-door me-3 fs-5"></i> Work From Home
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('employee.salaryslip') }}" 
               class="nav-link d-flex align-items-center p-2 rounded-3 fw-medium {{ request()->routeIs('employee.salaryslip') ? 'active-link' : 'text-white link-hover' }}">
                <i class="bi bi-cash-stack me-3 fs-5"></i> Salary Slip
            </a>
        </li>
    </ul>

    <hr class="border-light opacity-25 mt-auto">

    <!-- Footer (User Info) -->
    <div class="mt-auto pt-3 text-center">
        <p class="small mb-1 text-light opacity-75">Logged in as</p>
        <p class="fw-bold mb-1">{{ auth()->user()->name }}</p>
        <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.2); color: #fff;">
            {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>
</div>

<style>
/* === Premium Sidebar Look === */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 1050;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

/* Dynamic Avatar */
.logo-avatar {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: #fff;
    font-size: 24px;
    border: 2px solid rgba(255,255,255,0.3);
    text-transform: uppercase;
    transition: all 0.3s ease;
}
.logo-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 0 12px rgba(255, 75, 43, 0.6);
}

/* Hover links */
.link-hover {
    transition: all 0.3s ease;
    border-radius: 10px;
}
.link-hover:hover {
    background: rgba(255,255,255,0.15);
    transform: translateX(6px);
    text-decoration: none;
    color: #fff !important;
    box-shadow: 0 0 10px rgba(255,255,255,0.15);
}

/* Active Link */
.active-link {
    background: linear-gradient(90deg, #ff4b2b, #ff416c);
    color: #fff !important;
    box-shadow: 0 0 10px rgba(255, 65, 108, 0.5);
}

/* Icons */
.nav-link i {
    transition: transform 0.3s ease;
}
.nav-link:hover i {
    transform: scale(1.1);
}
</style>
