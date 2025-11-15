<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',

        // Salary Structure
        'gross_salary',
        'basic',
        'hra',
        'conveyance',
        'simple_allowance',
        'other_allowance',

        // Extra Earnings
        'incentive',
        'reward',

        // Period
        'month',
        'year',

        // Attendance
        'working_hours',
        'working_days',
        'half_days',
        'leaves',
        'sandwitch',
        'wfh',

        // Casual Leave
        'used_cl',
        'available_cl',
        'total_cl',

        // WFH related
        'wfh_dates',
        'wfh_percentages',
        'wfh_prices',
        'total_wfh_cost',
        'wfh_deduction_cost',

        // Alternative / Variable Pay
        'alternative_variable_pay',
        'alternative_variable_loses',

        // Deductions
        'leave_deduction',
        'half_day_deduction',
        'total_deduction',

        // PF / TAX
        'pf',
        'tds',

        // Final Computation
        'net_salary',
        'daily_rate',

        'status',
    ];

    protected $casts = [
        // JSON Fields
        'wfh_dates' => 'array',
        'wfh_percentages' => 'array',
        'wfh_prices' => 'array',

        // Decimal Fields
        'gross_salary' => 'decimal:2',
        'basic' => 'decimal:2',
        'hra' => 'decimal:2',
        'conveyance' => 'decimal:2',
        'simple_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',

        'incentive' => 'decimal:2',
        'reward' => 'decimal:2',

        'total_wfh_cost' => 'decimal:2',
        'wfh_deduction_cost' => 'decimal:2',

        'alternative_variable_pay' => 'decimal:2',
        'alternative_variable_loses' => 'decimal:2',

        'leave_deduction' => 'decimal:2',
        'half_day_deduction' => 'decimal:2',
        'total_deduction' => 'decimal:2',

        'pf' => 'decimal:2',
        'tds' => 'decimal:2',

        'net_salary' => 'decimal:2',
        'daily_rate' => 'decimal:2',

        'used_cl' => 'decimal:2',
        'available_cl' => 'decimal:2',
        'total_cl' => 'decimal:2',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to get month name
     */
    public function getMonthNameAttribute()
    {
        return date("F", mktime(0, 0, 0, $this->month, 1));
    }
}
