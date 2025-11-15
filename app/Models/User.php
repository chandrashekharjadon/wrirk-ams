<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_code',
        'role',
        'department_id',
        'designation_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin() { return $this->role === 'admin'; }
    public function isHR() { return $this->role === 'hr'; }
    public function isEmployee() { return $this->role === 'employee'; }


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function holidays()
    {
        return $this->belongsToMany(Holiday::class, 'holiday_user');
    }

    public function userSalary()
    {
        return $this->hasOne(UserSalary::class);
    }

    public function Profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function wfh()
    {
        return $this->hasOne(Wfh::class);
    }

    public function cl()
    {
        return $this->hasOne(CasualLeaveBalance::class);
    }
}
