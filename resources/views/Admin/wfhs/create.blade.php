@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-house-door-fill me-2"></i> Add Work From Home (WFH)
        </h2>
        <p class="text-light small opacity-75">
            Create and record a new WFH entry for an employee.
        </p>
    </div>

    <!-- Create Form Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">New WFH Record</h5>
            <a href="{{ route('admin.wfhs.index') }}" class="btn btn-premium-outline">
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

        {{-- ✅ Form --}}
        <form action="{{ route('admin.wfhs.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="row g-4">
                <!-- User -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-person-fill me-1 text-warning"></i> Employee
                    </label>
                    <select name="user_id" class="form-select glass-input select-dark" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback text-white">Please select an employee.</div>
                </div>

                <!-- Date -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-calendar3 me-1 text-warning"></i> Date
                    </label>
                    <input 
                        type="date" 
                        name="date" 
                        class="form-control glass-input text-white" 
                        value="{{ old('date') }}" 
                        required>
                    <div class="invalid-feedback text-white">Please select a valid date.</div>
                </div>

                <!-- Percent -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-graph-up-arrow me-1 text-warning"></i> Work Percentage
                    </label>
                    <input 
                        type="number" 
                        name="percent" 
                        class="form-control glass-input text-white" 
                        value="{{ old('percent', 100) }}" 
                        min="0" max="100" 
                        placeholder="Enter percent of work done" 
                        required>
                    <div class="invalid-feedback text-white">Please enter a valid percentage (0–100).</div>
                </div>

                <!-- Check In -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-clock-history me-1 text-warning"></i> Check In
                    </label>
                    <input 
                        type="time" 
                        name="check_in" 
                        class="form-control glass-input text-white" 
                        value="{{ old('check_in') }}" 
                        required>
                    <div class="invalid-feedback text-white">Please enter check-in time.</div>
                </div>

                <!-- Check Out -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-clock me-1 text-warning"></i> Check Out
                    </label>
                    <input 
                        type="time" 
                        name="check_out" 
                        class="form-control glass-input text-white" 
                        value="{{ old('check_out') }}" 
                        required>
                    <div class="invalid-feedback text-white">Please enter check-out time.</div>
                </div>

                <!-- Remark -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-chat-left-text-fill me-1 text-warning"></i> Remark
                    </label>
                    <textarea 
                        name="remark" 
                        class="form-control glass-input text-white" 
                        rows="3" 
                        placeholder="Enter remark (optional)">{{ old('remark') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-premium me-2 px-4">
                    <i class="bi bi-save me-1"></i> Create
                </button>
                <a href="{{ route('admin.wfhs.index') }}" class="btn btn-secondary px-4">
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

/* Fix dropdown colors */
.select-dark option {
    background-color: #fff !important;
    color: #000 !important;
}

/* Premium gradient button */
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
