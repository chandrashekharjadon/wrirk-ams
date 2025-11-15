@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Designation
        </h2>
        <p class="text-light small opacity-75">
            Modify details of an existing designation.
        </p>
    </div>

    <!-- Edit Form Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">Update Designation</h5>
            <a href="{{ route('admin.designations.index') }}" class="btn btn-premium-outline">
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
        <form action="{{ route('admin.designations.update', $designation->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-tag-fill me-1 text-warning"></i> Designation Name
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control glass-input" 
                        value="{{ old('name', $designation->name) }}" 
                        placeholder="Enter designation name" 
                        required>
                    <div class="invalid-feedback">Please enter a designation name.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-clipboard2-check-fill me-1 text-warning"></i> CL (Casual Leave)
                    </label>
                    <input 
                        type="number" 
                        name="cl" 
                        class="form-control glass-input" 
                        value="{{ old('cl', $designation->cl) }}" 
                        min="0" 
                        placeholder="Enter number of CLs" >
                </div>

                <!-- Department -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-building me-1 text-warning"></i> Department
                    </label>
                    <select name="department_id" class="form-select glass-input select-dark" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" 
                                {{ old('department_id', $designation->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select a department.</div>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold text-light">
                        <i class="bi bi-card-text me-1 text-warning"></i> Description
                    </label>
                    <textarea 
                        name="description" 
                        class="form-control glass-input" 
                        rows="3" 
                        placeholder="Enter description (optional)">{{ old('description', $designation->description) }}</textarea>
                </div>
            </div>

            <div class="mt-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-premium me-2 px-4">
                    <i class="bi bi-save me-1"></i> Update
                </button>
                <a href="{{ route('admin.designations.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ✅ Bootstrap validation --}}
{{-- Scripts --}}
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

/* Fix dropdown colors */
.select-dark option {
    background-color: #fff !important;
    color: #000 !important;
}

/* Button styles */
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
