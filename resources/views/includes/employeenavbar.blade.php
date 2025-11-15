<nav class="navbar navbar-expand-lg premium-navbar shadow-sm position-sticky top-0 z-3">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

        <!-- === LEFT SIDE: Back Button + Date & Time === -->
        <div class="d-flex align-items-center datetime-section">
            <!-- Elegant Back Icon -->
            <div class="back-icon me-3" onclick="window.history.back()" title="Go Back">
                <i class="bi bi-arrow-left"></i>
            </div>

            <!-- Date & Time -->
            <div class="datetime-display d-flex align-items-center text-white fw-semibold">
                <i class="bi bi-clock-history me-2"></i>
                <span id="current-date"></span>
                <span class="mx-1">|</span>
                <span id="current-time"></span>
            </div>
        </div>

        <!-- === RIGHT SIDE: User Dropdown === -->
        <div class="d-flex align-items-center">
            @php
                $userName = auth()->user()->name;
                $avatarText = collect(explode(' ', $userName))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center text-white fw-medium" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="nav-avatar d-flex align-items-center justify-content-center rounded-circle me-2">
                        <span class="fw-bold">{{ $avatarText }}</span>
                    </div>
                    {{ $userName }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 border-0">
                    <li>
                        <a class="dropdown-item" href="{{ route('employee.profile') }}">
                            <i class="bi bi-person-circle me-2"></i> Profile
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin() || auth()->user()->isHr())
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-arrow-left-right me-2"></i> Switch to Admin
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</nav>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- === DATE & TIME SCRIPT === -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' };
        const dateStr = now.toLocaleDateString('en-GB', options);
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('current-date').textContent = dateStr;
        document.getElementById('current-time').textContent = timeStr;
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
});
</script>

<style>
/* === Premium Navbar === */
.premium-navbar {
    background: linear-gradient(90deg, #6a11cb, #2575fc);
    color: #fff;
    border: none;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
    padding-top: 0.6rem;
    padding-bottom: 0.6rem;
}

/* === Left Section (Back + Date) === */
.datetime-section {
    margin-left: 250px; /* Adjust to sidebar width */
    transition: margin-left 0.3s ease;
}

/* Back Icon */
.back-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.back-icon:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.1);
    box-shadow: 0 0 8px rgba(255,255,255,0.3);
}

/* Date-Time */
.datetime-display {
    font-size: 15px;
    opacity: 0.95;
}

/* Avatar */
.nav-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: #fff;
    font-size: 14px;
    border: 2px solid rgba(255,255,255,0.3);
    text-transform: uppercase;
    transition: all 0.3s ease;
}
.nav-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 0 8px rgba(255,75,43,0.5);
}

/* Dropdown */
.dropdown-menu {
    background: #1f1f2e;
    color: #fff;
}
.dropdown-item {
    color: #fff;
}
.dropdown-item:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
}

/* === Responsive Adjustments === */
@media (max-width: 991.98px) {
    .datetime-section {
        margin-left: 0 !important;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
    }
    .back-icon {
        margin-bottom: 6px;
    }
    .datetime-display {
        font-size: 14px;
        justify-content: center;
    }
}
</style>
