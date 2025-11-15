<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aadhar',
        'pan',
        'acc_no',
        'ifsc_code',
        'joining_date',
        'mobile',
        'address',
        'pin_code',
        'profile_photo',
        'status',
    ];

    /**
     * Relationship: Profile belongs to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
