@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <!-- Page Header -->
    <div class="text-start mb-4">
        <h2 class="fw-bold page-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Attendance Details
        </h2>
        <p class="text-light small opacity-75">
            Modify and update monthly attendance summary & WFH details.
        </p>
    </div>

    <!-- Edit Form Card -->
    <div class="card dashboard-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-light fw-semibold mb-0">
                <i class="bi bi-calendar-check me-2 text-warning"></i> Update Attendance
            </h5>
            <a href="{{ route('admin.salaryslip.index') }}" class="btn btn-premium-outline">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>

        {{-- ✅ Success & Error --}}
        @if(session('success'))
            <div class="alert alert-success glass-card mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger glass-card mb-4">{{ session('error') }}</div>
        @endif

        {{-- 🧮 Attendance Form --}}
        <form action="{{ route('admin.salaryslip.update', $monthRecord->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- 🧾 Attendance Summary --}}
                <div class="col-12">
                    <h5 class="fw-bold text-warning border-bottom pb-2 mb-3">🧮 Attendance Summary</h5>
                    <div class="row g-3">
                        @php
                            $fields = [
                                ['name'=>'working_hours','label'=>'Working Hours'],
                                ['name'=>'working_days','label'=>'Working Days'],
                                ['name'=>'half_days','label'=>'Half Days'],
                                ['name'=>'leaves','label'=>'Leaves'],
                                ['name'=>'sandwitch','label'=>'Sandwich Days'],
                                ['name'=>'wfh','label'=>'WFH Count']
                            ];
                        @endphp

                        @foreach($fields as $field)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-light">
                                {{ $field['label'] }}
                            </label>
                            <input 
                                type="number" 
                                name="{{ $field['name'] }}"
                                class="form-control glass-input"
                                value="{{ old($field['name'], $monthRecord->{$field['name']}) }}"
                                min="0" 
                                step="0.01">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- 💻 WFH Details --}}
                <div class="col-12 mt-4">
                    <hr class="border-light mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-warning">🏠 Work From Home Details</h5>
                        <button type="button" id="addWfhRow" class="btn btn-premium btn-sm d-flex align-items-center gap-1">
                            <i class="bi bi-plus-circle"></i> Add WFH Row
                        </button>
                    </div>

                    <div id="wfhContainer">
                        @php
                            $wfhDates = is_array($monthRecord->wfh_dates) ? $monthRecord->wfh_dates : json_decode($monthRecord->wfh_dates, true);
                            $wfhPercentages = is_array($monthRecord->wfh_percentages) ? $monthRecord->wfh_percentages : json_decode($monthRecord->wfh_percentages, true);
                        @endphp

                        @forelse($wfhDates ?? [] as $index => $date)
                        <div class="row g-2 mb-2 wfh-row align-items-center border-bottom border-secondary pb-2">
                            <div class="col-md-5">
                                <input type="date" name="wfh_dates[]" value="{{ $date }}" class="form-control glass-input" />
                            </div>
                            <div class="col-md-5">
                                <input type="number" name="wfh_percentages[]" value="{{ $wfhPercentages[$index] ?? 0 }}"
                                    class="form-control glass-input" placeholder="Percentage (%)" />
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-danger btn-sm removeRow">❌</button>
                            </div>
                        </div>
                        @empty
                        <div class="text-muted small">No WFH entries. Click <b>“Add WFH Row”</b> to create one.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-premium me-2 px-4">
                    <i class="bi bi-save me-1"></i> Save Changes
                </button>
                <a href="{{ route('admin.salaryslip.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- JS for Dynamic WFH Rows --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('wfhContainer');
    const addBtn = document.getElementById('addWfhRow');

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.classList.add('row','g-2','mb-2','wfh-row','align-items-center','border-bottom','border-secondary','pb-2','fade-in');
        row.innerHTML = `
            <div class="col-md-5">
                <input type="date" name="wfh_dates[]" class="form-control glass-input" />
            </div>
            <div class="col-md-5">
                <input type="number" name="wfh_percentages[]" class="form-control glass-input" placeholder="Percentage (%)" />
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-danger btn-sm removeRow">❌</button>
            </div>
        `;
        container.appendChild(row);
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('.wfh-row').remove();
        }
    });
});
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
    box-shadow: 0 8px 22px rgba(0,0,0,0.4);
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
    border: 2px solid rgba(255,255,255,0.4);
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
.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
