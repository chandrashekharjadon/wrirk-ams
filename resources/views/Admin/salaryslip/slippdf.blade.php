<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Slip - {{ $monthRecord->user->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #212529;
            margin: 20px;
        }
        h2, h4, h5 {
            margin: 0;
            color: #007bff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .section-title {
            font-weight: bold;
            color: #007bff;
            margin-top: 15px;
            border-bottom: 1px solid #007bff;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Salary Slip</h2>
        <p><strong>{{ $monthRecord->user->name }}</strong> ({{ $monthRecord->user->employee_code ?? 'N/A' }})<br>
        {{ \Carbon\Carbon::create()->month($monthRecord->month)->format('F') }} {{ $monthRecord->year }}</p>
    </div>

    {{-- 👨‍💼 Employee Details --}}
    <h4 class="section-title">Employee Details</h4>
    <table>
        <tr><th>Name</th><td>{{ $monthRecord->user->name ?? 'N/A' }}</td></tr>
        <tr><th>Employee Code</th><td>{{ $monthRecord->user->employee_code ?? 'N/A' }}</td></tr>
        <tr><th>Department</th><td>{{ $monthRecord->user->department->name ?? 'N/A' }}</td></tr>
        <tr><th>Designation</th><td>{{ $monthRecord->user->designation->name ?? 'N/A' }}</td></tr>
    </table>

    {{-- 📊 Attendance --}}
    <h4 class="section-title">Attendance Summary</h4>
    <table>
        <tr><th>Working Hours</th><td>{{ $monthRecord->working_hours }}</td></tr>
        <tr><th>Working Days</th><td>{{ $monthRecord->working_days }}</td></tr>
        <tr><th>Leaves</th><td>{{ $monthRecord->leaves }}</td></tr>
        <tr><th>Half Days</th><td>{{ $monthRecord->half_days }}</td></tr>
        <tr><th>WFH</th><td>{{ $monthRecord->wfh }}</td></tr>
        <tr><th>Sandwich</th><td>{{ $monthRecord->sandwitch }}</td></tr>
    </table>

    {{-- 🏠 WFH Details --}}
    @php
        $wfhDates = is_array($monthRecord->wfh_dates) ? $monthRecord->wfh_dates : json_decode($monthRecord->wfh_dates,true);
        $wfhPercentages = is_array($monthRecord->wfh_percentages) ? $monthRecord->wfh_percentages : json_decode($monthRecord->wfh_percentages,true);
        $wfhPrices = is_array($monthRecord->wfh_prices) ? $monthRecord->wfh_prices : json_decode($monthRecord->wfh_prices,true);
    @endphp

    @if(!empty($wfhDates))
        <h4 class="section-title">Work From Home Breakdown</h4>
        <table>
            <thead>
                <tr><th>Date</th><th>Percentage</th><th>Amount (₹)</th></tr>
            </thead>
            <tbody>
                @foreach($wfhDates as $index => $date)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</td>
                        <td>{{ $wfhPercentages[$index] ?? 0 }}%</td>
                        <td>₹ {{ number_format($wfhPrices[$index] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><th colspan="2" class="text-success fw-bold text-end">Total WFH Amount</th>
                    <th class="text-success fw-bold">₹ {{ number_format($monthRecord->total_wfh_cost, 2) }}</th></tr>
            </tfoot>
        </table>
    @endif

    {{-- 💰 Salary --}}
    <h4 class="section-title">Salary Components</h4>
    <table>
        <tr><th>Basic</th><td>₹ {{ number_format($monthRecord->basic, 2) }}</td></tr>
        <tr><th>HRA</th><td>₹ {{ number_format($monthRecord->hra, 2) }}</td></tr>
        <tr><th>Conveyance</th><td>₹ {{ number_format($monthRecord->conveyance, 2) }}</td></tr>
        <tr><th>Allowances</th><td>₹ {{ number_format($monthRecord->simple_allowance + $monthRecord->other_allowance, 2) }}</td></tr>
        <tfoot>
            <tr><th class="text-success fw-bold">Gross Salary</th><td class="text-success fw-bold">₹ {{ number_format($monthRecord->gross_salary, 2) }}</td></tr>
        </tfoot>
    </table>

    {{-- ⚖️ Deductions --}}
    <h4 class="section-title">Deductions</h4>
    <table>
        <tr><th>Leave Deduction</th><td>₹ {{ number_format($monthRecord->leave_deduction, 2) }}</td></tr>
        <tr><th>Half Day Deduction</th><td>₹ {{ number_format($monthRecord->half_day_deduction, 2) }}</td></tr>
        <tr><th>Other Deductions</th><td>₹ {{ number_format($monthRecord->other_deduction ?? 0, 2) }}</td></tr>
        <tr><th class="text-danger fw-bold">Total Deductions</th><td class="text-danger fw-bold">₹ {{ number_format($monthRecord->total_deduction, 2) }}</td></tr>
    </table>

    {{-- ✅ Net Salary --}}
    @php
        $finalNet = ($monthRecord->gross_salary + $monthRecord->total_wfh_cost) - $monthRecord->total_deduction;
    @endphp

    <h4 class="section-title">Net Payable</h4>
    <h3 class="text-end text-success">₹ {{ number_format($finalNet, 2) }}</h3>

</body>
</html>
