<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-3 shadow-lg">
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

    <!-- Scrollable Menu -->
    <div class="menu-container flex-grow-1">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-speedometer2 me-2 fs-5"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.departments.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.departments.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-diagram-3 me-2 fs-5"></i> Departments
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.designations.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.designations.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-person-badge me-2 fs-5"></i> Designations
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.users.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.users.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-people me-2 fs-5"></i> Users
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.wfhs.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.wfhs.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-house-door me-2 fs-5"></i> WFHs
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.holidays.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.holidays.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-calendar-event me-2 fs-5"></i> Holidays
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.leaves.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.leaves.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-calendar-check me-2 fs-5"></i> Leaves
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.casual_leave_balances.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.casual_leave_balances.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-umbrella me-2 fs-5"></i> CL
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.salaryslip.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.salaryslip.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-file-earmark-text me-2 fs-5"></i> Salary Slip
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.fileupload.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.fileupload.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-upload me-2 fs-5"></i> Compare Attendance
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.attendance.index') }}"
                    class="nav-link text-white d-flex align-items-center p-2 rounded {{ request()->routeIs('admin.attendance.index') ? 'bg-success text-light' : 'hover-bg-light' }}">
                    <i class="bi bi-clipboard-data me-2 fs-5"></i> Monthly Report
                </a>
            </li>
            
        </ul>
    </div>

</div>

<style>
    /* === Fixed Sidebar === */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 260px;
        background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        border-bottom-right-radius: 24px;
        border-top-right-radius: 00px;
        overflow: hidden;
        z-index: 1050;
    }

    /* Scroll only inside menu */
    .menu-container {
        overflow-y: auto;
        flex-grow: 1;
        max-height:100vh;
        -ms-overflow-style: none;  /* Hide scrollbar for IE/Edge */
        scrollbar-width: none;      /* Hide scrollbar for Firefox */
    }

        /* Hide scrollbar for Chrome, Safari and Opera */
    .menu-container::-webkit-scrollbar {
        display: none;
    }

    .menu-container::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    /* Avatar */
    .logo-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        color: #fff;
        font-size: 24px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .logo-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 0 12px rgba(255, 75, 43, 0.6);
    }

    /* Hover & Active Links */
    .hover-bg-light:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(6px);
        transition: all 0.3s ease;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

</style>


