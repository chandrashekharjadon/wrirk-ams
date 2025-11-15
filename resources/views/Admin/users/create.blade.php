@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-person-plus-fill me-2"></i> Add User
        </h2>
        <p class="text-light small opacity-75">
            Create a new user account, assign department, role, designation, profile and salary.
        </p>
    </div>

    <!-- Form Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">New User Details</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-premium">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show glass-card mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM START --}}
        <form action="{{ route('admin.users.store') }}" method="POST" 
              class="needs-validation" novalidate autocomplete="off"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-person-badge-fill me-1 text-warning"></i> Full Name
                    </label>
                    <input type="text" name="name" class="form-control glass-input text-white"
                        value="{{ old('name') }}" placeholder="Enter full name" required>
                    <div class="invalid-feedback text-white">Please enter the user's name.</div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-envelope-at-fill me-1 text-warning"></i> Email Address
                    </label>
                    <input type="email" name="email" class="form-control glass-input text-white"
                        value="{{ old('email') }}" placeholder="Enter email" required>
                    <div class="invalid-feedback text-white">Please enter a valid email.</div>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-lock-fill me-1 text-warning"></i> Password
                    </label>
                    <input type="password" name="password" class="form-control glass-input text-white"
                        placeholder="Enter password" required>
                    <div class="invalid-feedback text-white">Please enter a password.</div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-shield-lock-fill me-1 text-warning"></i> Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" class="form-control glass-input text-white"
                        placeholder="Confirm password" required>
                    <div class="invalid-feedback text-white">Please confirm the password.</div>
                </div>

                <!-- Employee Code -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-upc-scan me-1 text-warning"></i> Employee Code
                    </label>
                    <input type="text" name="employee_code" class="form-control glass-input text-white"
                        value="{{ old('employee_code') }}" placeholder="Optional">
                </div>

                <!-- Role -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-person-gear me-1 text-warning"></i> Role
                    </label>
                    <select name="role" class="form-select glass-input select-dark" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback text-white">Please select a role.</div>
                </div>

                <!-- Department -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-building me-1 text-warning"></i> Department
                    </label>
                    <select name="department_id" class="form-select glass-input select-dark">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Designation -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-person-workspace me-1 text-warning"></i> Designation
                    </label>
                    <select name="designation_id" class="form-select glass-input select-dark">
                        <option value="">-- Select Designation --</option>
                        @foreach($designations as $des)
                            <option value="{{ $des->id }}" {{ old('designation_id') == $des->id ? 'selected' : '' }}>
                                {{ $des->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- PROFILE DETAILS SECTION -->
            <h5 class="text-light fw-semibold mt-5">Profile Details</h5>
            <div class="row g-4 mt-2">

                <!-- Profile Photo -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-image-fill me-1 text-warning"></i> Profile Photo
                    </label>
                    <input type="file" name="profile_photo" 
                           class="form-control glass-input text-white"
                           accept="image/*">
                    <small class="text-white-50">Allowed: JPG, PNG · Max 2MB</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                        <i class="bi bi-credit-card-2-front-fill me-1 text-warning"></i> Aadhar Number
                    </label>
                    <input type="text" name="aadhar" class="form-control glass-input text-white"
                        value="{{ old('aadhar') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                       <i class="bi bi-card-text me-1 text-warning"></i> PAN Number
                    </label>
                    <input type="text" name="pan" class="form-control glass-input text-white"
                        value="{{ old('pan') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-light">
                       <i class="bi bi-phone-fill me-1 text-warning"></i> Mobile Number
                    </label>
                    <input type="text" name="mobile" class="form-control glass-input text-white"
                        value="{{ old('mobile') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-light">
                       <i class="bi bi-bank2 me-1 text-warning"></i> Account Number
                    </label>
                    <input type="text" name="acc_no" class="form-control glass-input text-white"
                        value="{{ old('acc_no') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-light">
                       <i class="bi bi-bank me-1 text-warning"></i> IFSC Code
                    </label>
                    <input type="text" name="ifsc_code" class="form-control glass-input text-white"
                        value="{{ old('ifsc_code') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-light">
                        <i class="bi bi-calendar-event-fill me-1 text-warning"></i> Joining Date
                    </label>
                    <input type="date" name="joining_date" class="form-control glass-input text-white"
                        value="{{ old('joining_date') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-light">
                        <i class="bi bi-map-fill me-1 text-warning"></i> Address
                    </label>
                    <input type="text" name="address" class="form-control glass-input text-white"
                        value="{{ old('address') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-light">
                       <i class="bi bi-geo-alt-fill me-1 text-warning"></i> Pin Code
                    </label>
                    <input type="text" name="pin_code" class="form-control glass-input text-white"
                        value="{{ old('pin_code') }}">
                </div>

            </div>

            <!-- SALARY DETAILS SECTION -->
            <h5 class="text-light fw-semibold mt-5">Salary Details</h5>
            <div class="row g-4 mt-2">

                <div class="col-md-4">
                    <label class="form-label text-light">
                       <i class="bi bi-cash-stack me-1 text-warning"></i> Gross Salary
                    </label>
                    <input type="number" name="gross_salary" id="gross_salary"
                        class="form-control glass-input text-white"
                        value="{{ old('gross_salary') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                       <i class="bi bi-wallet2 me-1 text-warning"></i> Basic
                    </label>
                    <input type="number" name="basic" id="basic"
                        class="form-control glass-input text-white"
                        value="{{ old('basic') }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                        <i class="bi bi-house-door-fill me-1 text-warning"></i> HRA
                    </label>
                    <input type="number" name="hra" id="hra"
                        class="form-control glass-input text-white"
                        value="{{ old('hra') }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                        <i class="bi bi-truck-front-fill me-1 text-warning"></i> Conveyance
                    </label>
                    <input type="number" name="conveyance" id="conveyance"
                        class="form-control glass-input text-white"
                        value="{{ old('conveyance') }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                       <i class="bi bi-clipboard2-check-fill me-1 text-warning"></i> Simple Allowance
                    </label>
                    <input type="number" name="simple_allowance" id="simple_allowance"
                        class="form-control glass-input text-white"
                        value="{{ old('simple_allowance') }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">
                        <i class="bi bi-gift-fill me-1 text-warning"></i> Other Allowance
                    </label>
                    <input type="number" name="other_allowance" id="other_allowance"
                        class="form-control glass-input text-white"
                        value="{{ old('other_allowance') }}" readonly>
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-premium me-2 px-4">
                    <i class="bi bi-save me-1"></i> Create
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
            </div>

        </form>
        {{-- FORM END --}}
    </div>
</div>

{{-- Salary Auto-Calculation Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grossInput = document.getElementById('gross_salary');

    grossInput.addEventListener('input', function() {
        const gross = parseFloat(this.value) || 0;

        document.getElementById('basic').value            = (gross * 0.50).toFixed(2);
        document.getElementById('hra').value              = (gross * 0.20).toFixed(2);
        document.getElementById('conveyance').value       = (gross * 0.10).toFixed(2);
        document.getElementById('simple_allowance').value = (gross * 0.15).toFixed(2);
        document.getElementById('other_allowance').value  = (gross * 0.05).toFixed(2);
    });
});
</script>

{{-- Bootstrap Validation Script --}}
<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

{{-- Styles --}}
<style>
.page-title { color: #fff; }

.dashboard-card {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    color: #fff;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.4);
}

.glass-input {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
}
.glass-input:focus {
    background: rgba(255,255,255,0.15);
    border-color: #ff416c;
    box-shadow: 0 0 10px rgba(255,65,108,0.4);
    color: #fff;
}

.glass-input::placeholder {
    color: rgba(255,255,255,0.7);
}

.select-dark option {
    background-color: #fff !important;
    color: #000 !important;
}

.btn-premium {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none;
    border-radius: 999px;
    padding: 6px 16px;
    color: #fff;
    font-weight: 600;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255,65,108,0.3);
}
</style>

@endsection
