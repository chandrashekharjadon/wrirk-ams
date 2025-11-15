@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-people-fill me-2"></i> Manage Users
        </h2>
        <p class="text-light small opacity-75">
            View, edit, and manage all system users.
        </p>
    </div>

    <!-- 🔍 Filter Card -->
    <div class="card dashboard-card p-4 mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="name" class="form-label text-light opacity-85 fw-semibold">Select Name</label>
                <input type="text" name="name" id="name" class="form-control filter-input" value="{{ request('name') }}"
                    placeholder="Enter department name">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-premium me-2">
                    <i class="bi bi-funnel me-1 fs-5"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary reset-btn">
                    <i class="bi bi-arrow-clockwise me-1 fs-5"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-light fw-semibold mb-0">User List</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-premium d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg fs-6"></i> Add User
            </a>
        </div>

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show glass-card mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ✅ Users Table -->
        <div class="table-responsive">
            <table class="custom-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Emp Code</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="text-center">
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td class="fw-semibold text-light">
                                <i class="bi bi-person-circle me-1 text-warning"></i>{{ $user->name }}
                            </td>
                            <td class="text-light">{{ $user->email }}</td>
                            <td><span class="badge bg-secondary">{{ $user->employee_code }}</span></td>
                            <td>
                                <span class="badge 
                                    @if($user->role === 'admin') bg-danger 
                                    @elseif($user->role === 'hr') bg-info 
                                    @else bg-success 
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-light">{{ $user->department?->name ?? '—' }}</td>
                            <td class="text-light">{{ $user->designation?->name ?? '—' }}</td>

                            {{-- ✅ Compact Aligned Buttons --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="btn btn-outline-warning btn-action">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-outline-warning btn-action">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                          method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-action delete-btn">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-light opacity-75">
                                <i class="bi bi-info-circle me-2"></i>No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ✅ Pagination -->
        <div class="d-flex justify-content-end mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 Delete Confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: 'rgba(30,31,41,0.95)',
                color: '#fff',
                backdrop: `
                    rgba(0,0,0,0.6)
                    left top
                    no-repeat
                `,
                customClass: {
                    popup: 'swal-premium-popup',
                    confirmButton: 'swal-premium-confirm',
                    cancelButton: 'swal-premium-cancel'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
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

/* ✅ Compact Table */
.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 6px;
    font-size: 0.9rem;
}
.custom-table thead {
    background: rgba(255,255,255,0.08);
    color: #fff;
    font-weight: 600;
}
.custom-table th, .custom-table td {
    padding: 10px 12px;
    border: 1px solid rgba(255,255,255,0.08);
    vertical-align: middle;
}
.custom-table td {
    background: rgba(255,255,255,0.04);
    border-radius: 6px;
    color: rgba(255,255,255,0.9);
}
.custom-table tr:hover td {
    background: rgba(255,255,255,0.07);
    transition: all 0.3s ease;
}

/* Premium Button */
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

/* ✅ Compact Action Buttons */
.btn-action {
    padding: 4px 8px !important;
    font-size: 0.8rem !important;
    border-radius: 40px !important;
}
.btn-action i {
    font-size: 0.9rem;
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

/* ✅ SweetAlert Premium Theme */
.swal-premium-popup {
    border-radius: 20px !important;
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    padding: 2rem !important;
}
.swal-premium-confirm {
    background: linear-gradient(90deg, #ff416c, #ff4b2b) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 50px !important;
    padding: 8px 22px !important;
    font-weight: 600 !important;
    box-shadow: 0 0 20px rgba(255,65,108,0.4);
    transition: all 0.2s ease-in-out;
}
.swal-premium-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 25px rgba(255,65,108,0.6);
}
.swal-premium-cancel {
    background: transparent !important;
    border: 1px solid rgba(255,255,255,0.2) !important;
    color: #ddd !important;
    border-radius: 50px !important;
    padding: 8px 22px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease-in-out;
}
.swal-premium-cancel:hover {
    background: rgba(255,255,255,0.1) !important;
    color: #fff !important;
}

/* Pagination */
.pagination {
    --bs-pagination-color: #fff;
    --bs-pagination-bg: rgba(255,255,255,0.05);
    --bs-pagination-border-color: rgba(255,255,255,0.2);
}
.pagination .page-item.active .page-link {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none;
}
.pagination .page-link:hover {
    background: rgba(255,255,255,0.1);
}
</style>
@endsection
