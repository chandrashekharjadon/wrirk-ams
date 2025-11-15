@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold page-title mb-1">
                <i class="bi bi-cloud-arrow-up-fill me-2 text-info"></i> Admin Upload Attendance
            </h2>
            <p class="text-light small opacity-75 mb-0">
                Upload your attendance Excel sheet for automated record processing.
            </p>
        </div>
        <a href="{{ route('admin.compair.matching') }}" class="btn btn-premium-outline d-flex align-items-center gap-2">
            <i class="bi bi-search-heart fs-6"></i> Compare
        </a>
    </div>

    <!-- Upload Card -->
    <div class="card dashboard-card p-5">
        <h5 class="text-light fw-semibold mb-4">
            <i class="bi bi-file-earmark-arrow-up-fill me-2 text-info"></i> Upload Excel File
        </h5>

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show glass-card mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ❌ Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show glass-card mb-4" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- 📤 Upload Attendance Form -->
        <form action="{{ route('admin.fileupload.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-4">
                <div class="upload-box text-center p-5 rounded-4">
                    <input 
                        type="file" 
                        name="file" 
                        accept=".xlsx,.xls,.csv"
                        required
                        id="fileUpload"
                        class="d-none"
                    >
                    <label for="fileUpload" class="upload-label d-flex flex-column align-items-center justify-content-center text-light">
                        <i class="bi bi-cloud-upload-fill display-6 mb-2 text-info"></i>
                        <span id="uploadText" class="fw-semibold">Click to browse or drag & drop your file here</span>
                        <small class="text-secondary mt-1">(Accepted: .xlsx, .xls, .csv)</small>
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-premium d-flex align-items-center gap-2 px-4">
                    <i class="bi bi-upload fs-5"></i> Import Attendance
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ Script to show selected filename --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileUpload');
    const uploadText = document.getElementById('uploadText');

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            uploadText.textContent = `📄 ${fileInput.files[0].name} selected`;
        } else {
            uploadText.textContent = "Click to browse or drag & drop your file here";
        }
    });
});
</script>

{{-- ✅ Styles --}}
<style>
.page-title {
    color: #fff;
}

/* Glassmorphic Card */
.dashboard-card {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    color: #fff;
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
}

/* Upload Box */
.upload-box {
    background: rgba(255, 255, 255, 0.05);
    border: 2px dashed rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}
.upload-box:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: #00d4ff;
    transform: scale(1.02);
}

/* Upload Label */
.upload-label {
    cursor: pointer;
    transition: all 0.3s ease;
}
.upload-label:hover i {
    color: #00d4ff;
    transform: translateY(-3px);
}

/* Premium Gradient Button */
.btn-premium {
    background: linear-gradient(90deg, #00b09b, #96c93d);
    border: none;
    border-radius: 999px;
    color: #fff;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 176, 155, 0.25);
}

/* Premium Outline Button */
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
