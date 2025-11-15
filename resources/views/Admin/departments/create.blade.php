@extends('layouts.app')

@section('title', 'Add Department')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-building-add me-2"></i> Add Department
        </h2>
        <p class="text-light small opacity-75">
            Create a new department and add it to your organization.
        </p>
    </div>

    <!-- Add Department Card -->
    <div class="card dashboard-card p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">New Department Details</h5>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-premium-outline d-flex align-items-center gap-2">
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
        <form action="{{ route('admin.departments.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label fw-semibold text-light">Department Name <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control form-control-lg glass-input @error('name') is-invalid @enderror"
                    placeholder="Enter department name" 
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-premium d-flex align-items-center gap-2">
                    <i class="bi bi-save2-fill fs-6"></i> Create
                </button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary rounded-pill px-4">
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
