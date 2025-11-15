@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-calendar2-plus me-2"></i> Add Leave
        </h2>
        <p class="text-light small opacity-75">
            Assign a leave for an employee.
        </p>
    </div>

    <!-- Leave Form Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">New Leave Entry</h5>
            <a href="{{ route('admin.leaves.index') }}" class="btn btn-premium-outline">
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

        {{-- ✅ Leave Form --}}
        <form action="{{ route('admin.leaves.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row g-4">

                <!-- User -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-person-fill me-1 text-warning"></i> User
                    </label>
                    <select name="user_id" class="form-select glass-input select-dark" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback text-white">Please select a user.</div>
                </div>

                <!-- Date -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-calendar-event-fill me-1 text-warning"></i> Leave Date
                    </label>
                    <input type="date" name="date" class="form-control glass-input text-white" value="{{ old('date') }}" required>
                    <div class="invalid-feedback text-white">Please select a date.</div>
                </div>

                <!-- Reason -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-card-text me-1 text-warning"></i> Reason
                    </label>
                    <textarea name="reason" class="form-control glass-input text-white" rows="3" placeholder="Enter reason (optional)">{{ old('reason') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-premium me-2 px-4">
                    <i class="bi bi-save me-1"></i> Create
                </button>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ✅ Bootstrap Validation --}}
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

{{-- ✅ Styles --}}
<style>
.page-title {
    color: #fff;
}

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

.glass-input::placeholder,
textarea.glass-input::placeholder {
    color: rgba(255,255,255,0.7);
}

.select-dark option {
    background-color: #fff !important;
    color: #000 !important;
}

.btn-premium {
    background: linear-gradient(90deg, #00b09b, #96c93d);
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
.btn-premium-outline {
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.4);
    color: #fff;
    border-radius: 999px;
    padding: 6px 18px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-premium-outline:hover {
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.6);
}
</style>
@endsection
