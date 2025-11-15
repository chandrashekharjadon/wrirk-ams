<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasualLeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'used',
        'remaining',
        'year',
    ];

    /**
     * Relation to User (Employee)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
