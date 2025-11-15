@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-diagram-3-fill me-2"></i> Departments
        </h2>
        <p class="text-light small opacity-75">
            Manage all departments within the organization.
        </p>
    </div>

    <!-- 🔍 Filter Card -->
    <div class="card dashboard-card p-4 mb-4">
        <form method="GET" action="{{ route('admin.departments.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="name" class="form-label text-light opacity-85 fw-semibold">Select Name</label>
                <input type="text" name="name" id="name" class="form-control filter-input" value="{{ request('name') }}"
                    placeholder="Enter department name">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-premium me-2">
                    <i class="bi bi-funnel me-1 fs-5"></i> Filter
                </button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary reset-btn">
                    <i class="bi bi-arrow-clockwise me-1 fs-5"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Department List -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-light fw-semibold mb-0">Department List</h5>
            <a href="{{ route('admin.departments.create') }}" class="btn btn-premium d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg fs-6"></i> Add Department
            </a>
        </div>

        {{-- ✅ Success Message --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show glass-card mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- ✅ Table -->
        <div class="table-responsive">
            <table class="custom-table align-middle mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th width="20%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}</td>
                        <td class="fw-semibold text-light">{{ $dept->name }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.departments.edit', $dept->id) }}"
                                class="btn btn-sm btn-outline-warning me-2 rounded-pill px-3">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-btn">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-light opacity-75">
                            <i class="bi bi-info-circle me-2"></i>No departments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ✅ Pagination -->
        <div class="d-flex justify-content-end mt-4">
            {{ $departments->links('pagination::custom') }}
        </div>

    </div>
</div>

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle all delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This department will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4b2b',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    background: 'rgba(30,31,41,0.95)',
                    color: '#fff',
                    backdrop: `rgba(0,0,0,0.6)`,
                    customClass: {
                        popup: 'rounded-4 shadow-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

{{-- STYLES --}}
<style>
    .page-title {
        color: #fff;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        color: #fff;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.4);
    }

    /* Table */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table thead {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        font-weight: 600;
    }

    .custom-table th {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .custom-table td {
        padding: 14px 16px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.9);
    }

    .custom-table tr:hover td {
        background: rgba(255, 255, 255, 0.08);
        transition: all 0.3s ease;
    }

    /* Buttons */
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
        box-shadow: 0 10px 25px rgba(255, 65, 108, 0.2);
    }

    .btn-outline-warning {
        border-color: #ffc107;
        color: #ffc107;
    }

    .btn-outline-warning:hover {
        background: #ffc107;
        color: #000;
    }

    .btn-outline-danger {
        border-color: #ff4b2b;
        color: #ff4b2b;
    }

    .btn-outline-danger:hover {
        background: #ff4b2b;
        color: #fff;
    }

    /* Pagination */
    .pagination {
        --bs-pagination-color: #fff;
        --bs-pagination-bg: rgba(255, 255, 255, 0.05);
        --bs-pagination-border-color: rgba(255, 255, 255, 0.2);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        border: none;
    }

    .pagination .page-link:hover {
        background: rgba(255, 255, 255, 0.1);
    }
</style>
@endsection