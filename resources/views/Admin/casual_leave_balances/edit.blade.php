@extends('layouts.app')

@section('title', 'Edit Casual Leave Balance')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Casual Leave Balance
        </h2>
        <p class="text-light small opacity-75">
            Update casual leave details for the selected employee.
        </p>
    </div>

    <!-- Edit Leave Balance Card -->
    <div class="card dashboard-card p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">Update Leave Balance</h5>
            <a href="{{ route('admin.casual_leave_balances.index') }}" class="btn btn-premium-outline d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left-circle fs-6"></i> Back
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show glass-card mb-4" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.casual_leave_balances.update', $casual_leave_balance->id) }}" 
              method="POST" 
              class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- User --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        👤 Select User <span class="text-danger">*</span>
                    </label>
                    <select name="user_id" class="form-select glass-input text-dark" required>
                        <option value="">-- Choose User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                {{ old('user_id', $casual_leave_balance->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select a user.</div>
                </div>

                {{-- Total --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-light">📅 Total Leaves</label>
                    <input type="number" name="total" class="form-control glass-input text-white" 
                           value="{{ old('total', $casual_leave_balance->total ?? 0) }}" required>
                    <div class="invalid-feedback">Enter total leaves.</div>
                </div>

                {{-- Used --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-light">🧾 Used Leaves</label>
                    <input type="number" name="used" class="form-control glass-input text-white" 
                           value="{{ old('used', $casual_leave_balance->used ?? 0) }}" required>
                    <div class="invalid-feedback">Enter used leaves.</div>
                </div>

                {{-- Remaining --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-light">✅ Remaining</label>
                    <input type="number" name="remaining" class="form-control glass-input text-white" 
                           value="{{ old('remaining', $casual_leave_balance->remaining ?? 0) }}" required>
                    <div class="invalid-feedback">Enter remaining leaves.</div>
                </div>

                {{-- Year --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-light">📆 Year</label>
                    <input type="number" name="year" class="form-control glass-input text-white" 
                           value="{{ old('year', $casual_leave_balance->year ?? date('Y')) }}" required>
                    <div class="invalid-feedback">Enter valid year.</div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2 mt-5">
                <a href="{{ route('admin.casual_leave_balances.index') }}" class="btn btn-secondary rounded-pill px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-premium d-flex align-items-center gap-2">
                    <i class="bi bi-save2-fill fs-6"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ Styles --}}
<style>
.page-title {
    color: #fff;
}

/* Glassmorphic Card */
.dashboard-card {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 16px;
    color: #fff;
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    transition: all 0.3s ease;
}

/* Premium Buttons */
.btn-premium {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none;
    border-radius: 999px;
    padding: 8px 20px;
    color: #fff;
    font-weight: 600;
    letter-spacing: 0.4px;
    transition: all 0.3s ease;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255,65,108,0.25);
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

/* Inputs */
.glass-input {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    border-radius: 12px;
    padding: 12px 18px;
    transition: all 0.3s ease;
}
.glass-input:focus {
    background: rgba(255,255,255,0.12);
    border-color: #ff416c;
    box-shadow: 0 0 0 0.25rem rgba(255,65,108,0.25);
    color: #fff;
}
.glass-input::placeholder {
    color: rgba(255,255,255,0.6);
}

/* Validation */
.invalid-feedback {
    color: #ffb3b3;
    font-size: 0.9rem;
}

/* Secondary Button */
.btn-secondary {
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-secondary:hover {
    background: rgba(255,255,255,0.25);
}
</style>
@endsection
