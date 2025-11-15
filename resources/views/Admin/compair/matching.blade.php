@extends('layouts.app')

@section('title', 'Compare Attendance | Admin')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- 🧾 Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-white mb-1">
                <i class="bi bi-clipboard-check-fill text-info me-2"></i>
                Compare Attendance
            </h2>
            <p class="text-light opacity-75 mb-0">Compare Admin and HR attendance records.</p>
        </div>

        <a href="{{ route('admin.fileupload.index') }}" class="btn btn-premium-outline d-flex align-items-center gap-2">
            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Attendance
        </a>
    </div>

    <!-- 🔍 Filter Form -->
    <div class="card glass-card p-4 mb-5">
        <form method="GET" action="{{ route('admin.compair.matching') }}" class="row g-4 align-items-end">
            <div class="col-md-4">
                <label for="filter_date" class="form-label text-light fw-semibold">
                    <i class="bi bi-calendar2-event me-1 text-warning"></i> Select Date
                </label>
                <input 
                    type="date" 
                    id="filter_date" 
                    name="date" 
                    class="form-control glass-input text-dark" 
                    value="{{ request('date') }}"
                >
            </div>
            <div class="col-md-4 d-flex gap-3">
                <button type="submit" class="btn btn-premium fw-semibold flex-grow-1">
                    <i class="bi bi-search-heart me-2"></i> Compare
                </button>
                <a href="{{ route('admin.compair.matching') }}" class="btn btn-premium-outline fw-semibold flex-grow-1">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 🧩 Comparison Section -->
    @if (request('date'))
        @forelse ($mergedData as $employeeCode => $records)
            @php
                $admin = $records['admin'] ?? null;
                $hr = $records['hr'] ?? null;

                $checkInMismatch = ($admin && $hr && $admin->check_in != $hr->check_in);
                $checkOutMismatch = ($admin && $hr && $admin->check_out != $hr->check_out);
            @endphp

            <!-- 🔷 Attendance Card -->
            <div class="card glass-card mb-4 attendanceCard border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center border-0 rounded-top p-3"
                     style="background: linear-gradient(90deg, #1e3c72, #2a5298);">
                    <div>
                        <h5 class="fw-bold text-white mb-0">
                            <i class="bi bi-person-circle me-2 text-info"></i>
                            {{ $admin?->user?->name ?? $hr?->user?->name ?? 'Unknown Employee' }}
                            <span class="text-light opacity-75 small">
                                ({{ $hr?->user?->employee_code }})
                            </span>
                        </h5>
                    </div>
                    <div>
                        <button class="btn btn-success btn-sm me-2 move-btn fw-semibold" data-type="hr">
                            <i class="bi bi-check-circle me-1"></i> Accept HR
                        </button>
                        <button class="btn btn-danger btn-sm move-btn fw-semibold" data-type="admin">
                            <i class="bi bi-x-circle me-1"></i> Accept Admin
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Admin Section -->
                        <div class="col-md-6">
                            <div class="p-3 rounded border-start border-4 {{ $checkInMismatch || $checkOutMismatch ? 'border-warning' : ($admin?->status == 'Absent' ? 'border-danger' : 'border-success') }}"
                                 style="background: rgba(13, 110, 253, 0.05);">
                                <h6 class="text-info fw-bold mb-3">
                                    <i class="bi bi-shield-lock me-1"></i> Admin Data
                                </h6>
                                @if ($admin)
                                    <ul class="list-unstyled small mb-0 text-light">
                                        <li><strong>Emp_Code:</strong> {{ $admin?->user?->employee_code }}</li>
                                        <li><strong>Date:</strong> {{ $admin->date }}</li>
                                        <li class="{{ $checkInMismatch ? 'text-warning fw-bold' : '' }}">
                                            <strong>Check-in:</strong> {{ $admin->check_in }}
                                        </li>
                                        <li class="{{ $checkOutMismatch ? 'text-warning fw-bold' : '' }}">
                                            <strong>Check-out:</strong> {{ $admin->check_out }}
                                        </li>
                                        <li><strong>Status:</strong> {{ $admin->status }}</li>
                                        <li><strong>Work Hours:</strong> {{ $admin->working_hours }}</li>
                                    </ul>
                                @else
                                    <p class="text-muted fst-italic mb-0">No Admin data found.</p>
                                @endif   
                            </div>
                        </div>

                        <!-- HR Section -->
                        <div class="col-md-6">
                            <div class="p-3 rounded border-start border-4 {{ $checkInMismatch || $checkOutMismatch ? 'border-warning' : ($hr?->status == 'Absent' ? 'border-danger' : 'border-success') }}"
                                 style="background: rgba(40, 167, 69, 0.05);">
                                <h6 class="text-success fw-bold mb-3">
                                    <i class="bi bi-briefcase me-1"></i> HR Data
                                </h6>
                                @if ($hr)
                                    <ul class="list-unstyled small mb-0 text-light">
                                        <li><strong>Emp_Code:</strong> {{ $hr?->user?->employee_code }}</li>
                                        <li><strong>Date:</strong> {{ $hr->date }}</li>
                                        <li class="{{ $checkInMismatch ? 'text-warning fw-bold' : '' }}">
                                            <strong>Check-in:</strong> {{ $hr->check_in }}
                                        </li>
                                        <li class="{{ $checkOutMismatch ? 'text-warning fw-bold' : '' }}">
                                            <strong>Check-out:</strong> {{ $hr->check_out }}
                                        </li>
                                        <li><strong>Status:</strong> {{ $hr->status }}</li>
                                        <li><strong>Work Hours:</strong> {{ $hr->working_hours }}</li>
                                        @if(strtolower($hr->status) === 'wfh')
                                            <li><strong>WFH %:</strong> {{ $hr->wfh_percentage ?? '-' }}</li>
                                        @endif
                                        <li><strong>Remark:</strong> {{ $hr->remark ?? '-' }}</li>
                                    </ul>
                                @else
                                    <p class="text-muted fst-italic mb-0">No HR data found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-white-faded text-muted mt-5">No attendance data available for this date.</p>
        @endforelse
    @else
        <p class="text-center text-white-faded mt-5">Please select a date and click Compare.</p>
    @endif

    <!-- ✅ Selected Section -->
    <div id="selectedDiv" class="mt-5">
        <h4 class="fw-bold text-white mb-3">✅ Selected Records</h4>
        <div id="selectedRecordsContainer" class="mb-3"></div>
        <button id="submitSelected" class="btn btn-premium" disabled>Submit Selected</button>
    </div>
</div>

<!-- 🧠 Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const moveButtons = document.querySelectorAll('.move-btn');
    const selectedContainer = document.getElementById('selectedRecordsContainer');
    const attendanceCards = document.querySelectorAll('.attendanceCard');
    const submitBtn = document.getElementById('submitSelected');

    function checkAllMoved() {
        const allMoved = Array.from(attendanceCards).every(card => card.style.display === 'none');
        submitBtn.disabled = !allMoved;
    }

    moveButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.attendanceCard');
            const type = btn.dataset.type;

            const selectedData = type === 'hr'
                ? card.querySelector('.col-md-6:nth-child(2)').innerHTML
                : card.querySelector('.col-md-6:first-child').innerHTML;

            const employeeName = card.querySelector('h5').textContent.trim();
            const employeeCode = employeeName.match(/\((.*?)\)/) ? employeeName.match(/\((.*?)\)/)[1] : '';
            const cleanName = employeeName.replace(/\(.*?\)/, '').trim();

            const selectedCard = document.createElement('div');
            selectedCard.classList.add('card', 'glass-card', 'p-3', 'mb-3');
            selectedCard.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge bg-${type === 'hr' ? 'success' : 'primary'}">${type.toUpperCase()} SELECTED</span>
                    <button class="btn btn-warning btn-sm retrieve-btn">↩ Retrieve</button>
                </div>
                <div class="employee-meta" 
                    data-source="${type.toUpperCase()}" 
                    data-employee-name="${cleanName}" 
                    data-employee-code="${employeeCode}">
                    ${selectedData}
                </div>
            `;

            selectedContainer.appendChild(selectedCard);
            card.style.display = 'none';
            checkAllMoved();

            selectedCard.querySelector('.retrieve-btn').addEventListener('click', () => {
                selectedCard.remove();
                card.style.display = 'block';
                checkAllMoved();
            });
        });
    });

    submitBtn.addEventListener('click', async () => {
        const selectedCards = selectedContainer.querySelectorAll('.card');
        if (selectedCards.length === 0) return alert('No records selected.');

        const records = Array.from(selectedCards).map(card => {
            const meta = card.querySelector('.employee-meta');
            const source = meta.dataset.source;
            const employee_name = meta.dataset.employeeName;
            const employee_code = meta.dataset.employeeCode;
            const getText = (label) => {
                const item = Array.from(card.querySelectorAll('li')).find(li => li.textContent.includes(label));
                return item ? item.textContent.replace(label, '').trim() : '';
            };
            return {
                employee_name,
                employee_code,
                date: getText('Date:'),
                check_in: getText('Check-in:'),
                check_out: getText('Check-out:'),
                status: getText('Status:'),
                work_hours: getText('Work Hours:'),
                remark: getText('Remark:'),
                wfh_percentage: getText('WFH %:'),
                source
            };
        });

        const response = await fetch("{{ route('admin.compair.saveFinal') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ records })
        });

        const result = await response.json();
        alert(result.status === 'success' ? '✅ ' + result.message : '❌ ' + result.message);
    });
});
</script>

<!-- 🎨 Styles -->
<style>
.glass-card {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    backdrop-filter: blur(12px);
    color: #fff;
    transition: all 0.3s ease;
}
.glass-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 20px rgba(0,0,0,0.25);
}
.glass-subcard {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
}
.btn-premium {
    background: linear-gradient(90deg, #00b09b, #96c93d);
    border: none;
    color: #fff;
    border-radius: 999px;
    transition: 0.3s;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 15px rgba(0, 176, 155, 0.4);
}
.btn-premium-outline {
    border: 2px solid rgba(255,255,255,0.4);
    color: #fff;
    border-radius: 999px;
    transition: 0.3s;
}
.btn-premium-outline:hover {
    background: rgba(255,255,255,0.1);
}
.text-white-faded {
    color: rgba(255, 255, 255, 0.85) !important;
}
</style>
@endsection
