<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wfh extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'percent',
        'check_in',
        'check_out',
        'working_hours',
        'remark',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

