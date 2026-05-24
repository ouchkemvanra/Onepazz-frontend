<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name', 'full_name_kh', 'email', 'password',
        'phone', 'avatar_url', 'preferred_lang', 'preferred_currency',
        'date_of_birth', 'gender', 'role', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth'     => 'date',
        'is_active'         => 'boolean',
    ];

    public function isPlatformAdmin(): bool { return $this->role === 'platform_admin'; }
    public function isEmployerAdmin(): bool { return $this->role === 'employer_admin'; }
    public function isGymAdmin(): bool      { return $this->role === 'gym_admin'; }
    public function isMember(): bool        { return $this->role === 'member'; }

    public function employees()        { return $this->hasMany(Employee::class); }
    public function checkins()         { return $this->hasMany(Checkin::class); }
    public function gymReviews()       { return $this->hasMany(GymReview::class); }
    public function savedGyms()        { return $this->belongsToMany(Gym::class, 'saved_gyms', 'user_id', 'gym_id')->withPivot('saved_at'); }
    public function adminForEmployer() { return $this->hasOne(Employer::class, 'admin_user_id'); }
    public function adminForGym()      { return $this->hasOne(Gym::class, 'admin_user_id'); }
}
