<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSalary extends Model
{
    protected $fillable = [
        'user_id',
        'gross_salary',
        'basic',
        'hra',
        'conveyance',
        'simple_allowance',
        'other_allowance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

