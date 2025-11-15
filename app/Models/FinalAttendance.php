<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalAttendance extends Model
{
    use HasFactory;

    protected $table = 'final_attendance';

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'day',
        'working_hours',
        'status',
        'source',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

