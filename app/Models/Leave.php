<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date', // or 'datetime' if it includes time
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

