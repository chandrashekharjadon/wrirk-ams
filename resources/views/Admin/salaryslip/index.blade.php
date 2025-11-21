@extends('layouts.app')

@section('content')
<div class="dashboard-page d-flex">
    <main class="flex-fill">
        <div class="container-fluid px-4 py-5">

            <!-- Page Title -->
            <div class="text-start mb-4">
                <h2 class="fw-bold page-title">💼 Salary Slip</h2>
                <p class="text-light small opacity-75">View your detailed salary statement with a clean premium design.
                </p>
            </div>

            <!-- Filter Section -->
            <!-- 🧾 Employee Salary Slip Filter -->
            <div class="card dashboard-card p-4 mb-4 glass-card text-light">
                <form method="GET" action="{{ route('admin.salaryslip.index') }}" class="row g-3 align-items-end">

                    {{-- 👤 Employee --}}
                    <div class="col-md-3">
                        <label for="user_id" class="form-label text-light fw-semibold">👤 Select Employee</label>
                        <select name="user_id" id="user_id" class="form-select filter-input" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->employee_code }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 📅 Month --}}
                    <div class="col-md-3">
                        <label for="month" class="form-label text-light fw-semibold">📅 Select Month</label>
                        <select name="month" id="month" class="form-select filter-input">
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 📆 Year --}}
                    <div class="col-md-3">
                        <label for="year" class="form-label text-light fw-semibold">📆 Select Year</label>
                        <input type="number" name="year" id="year" class="form-control filter-input"
                            value="{{ request('year', now()->year) }}" min="2020" required>
                    </div>

                    {{-- 🔘 Buttons --}}
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-premium d-flex align-items-center gap-2 w-100">
                            <i class="bi bi-funnel me-1 fs-5"></i> Filter
                        </button>

                        <a href="{{ route('admin.salaryslip.index') }}"
                            class="btn btn-secondary d-flex align-items-center gap-2 w-100">
                            <i class="bi bi-arrow-clockwise fs-5"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Salary Slip Display -->
            @if(isset($monthRecord) && $monthRecord)
            <div class="card dashboard-card p-4 mb-5">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="fw-bold text-light">
                        💼 Salary Slip — {{ \Carbon\Carbon::create()->month($monthRecord->month)->format('F') }} / {{
                        $monthRecord->year }}
                    </h5>

                    <div class="d-flex gap-2">
                        @if($monthRecord->status === 'approved' || $monthRecord->status === 'update and approved')
                        <a href="{{ route('admin.salaryslip.pdf', $monthRecord) }}" target="_blank"
                            class="btn btn-premium d-flex align-items-center gap-2">
                            <i class="bi bi-printer-fill fs-5"></i> Print PDF
                        </a>
                        @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.salaryslip.edit', $monthRecord) }}"
                            class="btn btn-warning d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-fill fs-5"></i> Edit
                        </a>
                        <a href="{{ route('admin.salaryslip.approve', $monthRecord) }}"
                            class="btn btn-success d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill fs-5"></i> Approve
                        </a>
                        @else
                        <div class="alert alert-info py-1 px-3 mb-0">⏳ Awaiting Approval</div>
                        @endif
                    </div>
                </div>

                <!-- Employee & Attendance -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 glass-card">
                            <h6 class="section-header">👨‍💼 Employee Details</h6>
                            <table class="table table-borderless mb-0 text-light">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $monthRecord->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Employee Code:</th>
                                    <td>{{ $monthRecord->user->employee_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $monthRecord->user->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Designation:</th>
                                    <td>{{ $monthRecord->user->designation->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="p-3 glass-card">
                            <h6 class="section-header">📊 Attendance Summary</h6>
                            <table class="table table-borderless mb-0 text-light">
                                <tr>
                                    <th>Working Hours:</th>
                                    <td>{{ $monthRecord->working_hours }}</td>
                                </tr>
                                <tr>
                                    <th>Working Days:</th>
                                    <td>{{ $monthRecord->working_days }}</td>
                                </tr>
                                <tr>
                                    <th>Leaves:</th>
                                    <td>{{ $monthRecord->leaves }}</td>
                                </tr>
                                <tr>
                                    <th>Half Days:</th>
                                    <td>{{ $monthRecord->half_days }}</td>
                                </tr>
                                <tr>
                                    <th>WFH:</th>
                                    <td>{{ $monthRecord->wfh }}</td>
                                </tr>
                                <tr>
                                    <th>Sandwich:</th>
                                    <td>{{ $monthRecord->sandwitch }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- WFH Breakdown -->
                @php
                $wfhDates = $monthRecord->wfh_dates ?? [];
                $wfhPercentages = $monthRecord->wfh_percentages ?? [];
                $wfhPrices =    $monthRecord->wfh_prices ?? [];
                @endphp

                @if(!empty($wfhDates))
                <div class="p-3 glass-card mb-4">
                    <h6 class="section-header">🏡 Work From Home Breakdown</h6>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 custom-table text-light">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Percentage</th>
                                    <th>Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wfhDates as $i => $date)
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</strong></td>
                                    <td>{{ $wfhPercentages[$i] ?? 0 }}%</td>
                                    <td>₹ {{ number_format($wfhPrices[$i] ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="fw-semibold text-light text-end">Total WFH Amount:</td>
                                    <td class="fw-bold text-light">₹ {{ number_format($monthRecord->total_wfh_cost, 2)
                                        }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Salary & Deductions -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 glass-card">
                            <h6 class="section-header">💰 Salary Components</h6>
                            <table class="table table-borderless mb-0 text-light">
                                <tr>
                                    <th>Basic:</th>
                                    <td>₹ {{ number_format($monthRecord->basic, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>HRA:</th>
                                    <td>₹ {{ number_format($monthRecord->hra, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Conveyance:</th>
                                    <td>₹ {{ number_format($monthRecord->conveyance, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Allowances:</th>
                                    <td>₹ {{ number_format(($monthRecord->simple_allowance +
                                        $monthRecord->other_allowance), 2) }}</td>
                                </tr>
                                <tr class="fw-bold border-top text-danger">
                                    <th>Gross Salary:</th>
                                    <td>₹ {{ number_format($monthRecord->gross_salary, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 glass-card">
                            <h6 class="section-header">⚖️ Deductions</h6>
                            <table class="table table-borderless mb-0 text-light">
                                <tr>
                                    <th>Leave Deduction:</th>
                                    <td>₹ {{ number_format($monthRecord->leave_deduction, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Half Day Deduction:</th>
                                    <td>₹ {{ number_format($monthRecord->half_day_deduction, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Wfh Deductions:</th>
                                    <td>₹ {{ number_format($monthRecord->wfh_deduction_cost ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Other Deductions:</th>
                                    <td>₹ {{ number_format($monthRecord->other_deduction ?? 0, 2) }}</td>
                                </tr>
                                <tr class="fw-bold border-top text-danger">
                                    <th>Total Deductions:</th>
                                    <td>₹ {{ number_format($monthRecord->total_deduction, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Net Salary -->
                <div class="net-salary-box mt-4">
                    💵 Net Salary Payable: <span>₹ {{ number_format($monthRecord->net_salary, 2) }}</span>
                </div>

            </div>
            @else
            <div class="card dashboard-card text-center p-5">
                <h5 class="text-light opacity-85 mb-2">⚠️ No salary slip found</h5>
                <p class="text-light opacity-75">Select employee, month, and year to view the salary slip.</p>
            </div>
            @endif

        </div>
    </main>
</div>

<style>
    /* Background */
    .dashboard-page {
        min-height: 100vh;
        background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
        color: #fff;
        font-family: 'Poppins', sans-serif;
    }

    /* Card */
    .dashboard-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        backdrop-filter: blur(10px);
        color: #fff;
    }

    /* Glass inner cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    /* Section headers */
    .section-header {
        color: #f5f5f5;
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        padding-bottom: 5px;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Equal height fix */
    .equal-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Table styling */
    .table.table-borderless th,
    .table.table-borderless td {
        background: transparent !important;
        color: #fff !important;
        padding: 6px 8px;
        vertical-align: middle;
        border: none;
    }

    .table.table-borderless th {
        font-weight: 600;
        opacity: 0.9;
    }

    /* WFH-style custom table */
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

    /* Buttons — compact height */
    .btn-premium,
    .btn-secondary {
        padding: 6px 14px;
        font-size: 0.9rem;
        border-radius: 999px;
    }

    /* Premium button */
    .btn-premium {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        border: none;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(255, 65, 108, 0.12);
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 65, 108, 0.25);
    }

    /* Secondary button */
    .btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: #fff;
        font-weight: 600;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Net salary section */
    .net-salary-box {
        background: linear-gradient(90deg, #ff416c, #ff4b2b);
        border-radius: 12px;
        padding: 18px 24px;
        font-weight: 700;
        font-size: 1.3rem;
        text-align: center;
        color: #fff;
        box-shadow: 0 6px 18px rgba(255, 65, 108, 0.25);
    }

    .net-salary-box span {
        color: #fff;
        font-weight: 700;
    }
</style>
@endsection