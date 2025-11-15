<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'day',
        'working_hours',
        'status',
        'wfh_percentage',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
