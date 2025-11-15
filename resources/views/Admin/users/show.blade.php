@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-person-vcard-fill me-2"></i> User Details
        </h2>
        <p class="text-light small opacity-75">
            Full profile, department, role, salary, and personal information.
        </p>
    </div>

    <!-- Card -->
    <div class="card dashboard-card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold ms-2 mb-0">User Overview</h5>

            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-premium me-2">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <!-- USER BASIC INFO -->
        <div class="row g-4 align-items-center">

            <!-- Profile Photo + Basic Info -->
            <div class="col-md-4 d-flex flex-column align-items-center">

                <div class="profile-pic-wrapper">
                    @if($user->profile && $user->profile->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile->profile_photo) }}" class="profile-photo">
                    @else
                    <div class="default-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    @endif
                </div>

                <h4 class="mt-3 text-light fw-bold">{{ $user->name }}</h4>
                <p class="text-white-50">{{ $user->email }}</p>

                <span class="badge bg-gradient-pink px-3 py-2 mt-2 rounded-pill">
                    <i class="bi bi-award-fill me-1"></i> {{ ucfirst($user->role) }}
                </span>

            </div>

            <!-- Details -->
            <div class="col-md-8">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="text-light fw-semibold">
                            <i class="bi bi-building text-warning me-1"></i> Department
                        </label>
                        <div class="glass-box">{{ $user->department->name ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-light fw-semibold">
                            <i class="bi bi-person-workspace me-1 text-warning"></i> Designation
                        </label>
                        <div class="glass-box">{{ $user->designation->name ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-light fw-semibold">
                            <i class="bi bi-upc-scan me-1 text-warning"></i> Employee Code
                        </label>
                        <div class="glass-box">{{ $user->employee_code ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-light fw-semibold">
                            <i class="bi bi-envelope-at-fill me-1 text-warning"></i> Official Email
                        </label>
                        <div class="glass-box">{{ $user->email }}</div>
                    </div>

                </div>
            </div>

        </div>

        <hr class="my-4 border-light">

        <!-- PROFILE DETAILS -->
        <h5 class="text-light fw-semibold">Profile Details</h5>

        <div class="row g-4 mt-1">

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-credit-card-2-front-fill me-1 text-warning"></i> Aadhar Number
                </label>
                <div class="glass-box">{{ $user->profile->aadhar ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-card-text me-1 text-warning"></i> PAN Number
                </label>
                <div class="glass-box">{{ $user->profile->pan ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-phone-fill me-1 text-warning"></i> Mobile Number
                </label>
                <div class="glass-box">{{ $user->profile->mobile ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-bank2 me-1 text-warning"></i> Account Number
                </label>
                <div class="glass-box">{{ $user->profile->acc_no ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-bank me-1 text-warning"></i> IFSC Code
                </label>
                <div class="glass-box">{{ $user->profile->ifsc_code ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-calendar-event-fill me-1 text-warning"></i> Joining Date
                </label>
                <div class="glass-box">{{ $user->profile->joining_date ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-map-fill me-1 text-warning"></i> Address
                </label>
                <div class="glass-box">{{ $user->profile->address ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-geo-alt-fill me-1 text-warning"></i> Pin Code
                </label>
                <div class="glass-box">{{ $user->profile->pin_code ?? 'N/A' }}</div>
            </div>

        </div>

        <hr class="my-4 border-light">

        <!-- SALARY DETAILS -->
        <h5 class="text-light fw-semibold">Salary Details</h5>

        <div class="row g-4 mt-1">

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-cash-stack me-1 text-warning"></i> Gross Salary
                </label>
                <div class="glass-box">{{ $user->userSalary->gross_salary ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-wallet2 me-1 text-warning"></i> Basic
                </label>
                <div class="glass-box">{{ $user->userSalary->basic ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-house-door-fill me-1 text-warning"></i> HRA
                </label>
                <div class="glass-box">{{ $user->userSalary->hra ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-truck-front-fill me-1 text-warning"></i> Conveyance
                </label>
                <div class="glass-box">{{ $user->userSalary->conveyance ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-clipboard2-check-fill me-1 text-warning"></i> Simple Allowance
                </label>
                <div class="glass-box">{{ $user->userSalary->simple_allowance ?? 'N/A' }}</div>
            </div>

            <div class="col-md-4">
                <label class="text-light">
                    <i class="bi bi-gift-fill me-1 text-warning"></i> Other Allowance
                </label>
                <div class="glass-box">{{ $user->userSalary->other_allowance ?? 'N/A' }}</div>
            </div>

        </div>

    </div>
</div>

<!-- ========================= CUSTOM CSS ========================= -->
<style>
    .page-title {
        color: #fff;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.07);
        border-radius: 14px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, .4);
        color: #fff;
    }

    /* Profile wrapper with animated gradient */
    .profile-pic-wrapper {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, #ff416c, #ff4b2b, #6a11cb, #2575fc);
        background-size: 300% 300%;
        animation: gradientFlow 4s ease infinite;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes gradientFlow {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
    }

    .glass-box {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 10px 12px;
        border-radius: 10px;
        color: #fff;
        transition: 0.3s ease;
    }

    .glass-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(255, 255, 255, 0.12);
    }

    .btn-premium {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        border-radius: 999px;
        padding: 6px 16px;
        color: #fff;
        border: none;
        font-weight: bold;
    }

    .bg-gradient-pink {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        color: #fff !important;
    }
</style>
@endsection