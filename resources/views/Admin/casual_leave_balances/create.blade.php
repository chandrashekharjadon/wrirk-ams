@extends('layouts.app')

@section('title', 'Add Casual Leave Balance')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-calendar2-week-fill me-2 text-warning"></i> Add Casual Leave Balance
        </h2>
        <p class="text-light small opacity-75">
            Add a new leave balance record for a specific user.
        </p>
    </div>

    <!-- Form Card -->
    <div class="card dashboard-card p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">New Balance Details</h5>
            <a href="{{ route('admin.casual_leave_balances.index') }}" 
               class="btn btn-premium-outline d-flex align-items-center gap-2">
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

        {{-- Create Form --}}
        <form action="{{ route('admin.casual_leave_balances.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="row g-4">
                <!-- User -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-person-badge me-1 text-warning"></i> Select User <span class="text-danger">*</span>
                    </label>
                    <select name="user_id" class="form-select glass-input text-dark @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-clipboard-check me-1 text-success"></i> Total Leaves <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="total" class="form-control glass-input @error('total') is-invalid @enderror"
                        placeholder="Enter total leaves" value="{{ old('total', 0) }}" required>
                    @error('total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Used -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-hourglass-split me-1 text-info"></i> Used Leaves <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="used" class="form-control glass-input @error('used') is-invalid @enderror"
                        placeholder="Enter used leaves" value="{{ old('used', 0) }}" required>
                    @error('used')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remaining -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-check-circle-fill me-1 text-success"></i> Remaining Leaves <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="remaining" class="form-control glass-input @error('remaining') is-invalid @enderror"
                        placeholder="Enter remaining leaves" value="{{ old('remaining', 0) }}" required>
                    @error('remaining')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Year -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-calendar3 me-1 text-primary"></i> Year <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="year" class="form-control glass-input @error('year') is-invalid @enderror"
                        placeholder="Enter year" value="{{ old('year', date('Y')) }}" required>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <button type="submit" class="btn btn-premium d-flex align-items-center gap-2">
                    <i class="bi bi-save2-fill fs-6"></i> Add Balance
                </button>
                <a href="{{ route('admin.casual_leave_balances.index') }}" class="btn btn-secondary rounded-pill px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
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
    background: linear-gradient(90deg, #00b09b, #96c93d);
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
    box-shadow: 0 10px 25px rgba(0,176,155,0.25);
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

/* Input Fields */
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
    border-color: #00b09b;
    box-shadow: 0 0 0 0.25rem rgba(0,176,155,0.25);
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
