<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymStaff extends Model
{
    use SoftDeletes;

    protected $table = 'gym_staff';

    protected $fillable = [
        'user_id', 'gym_id', 'role', 'is_active', 'invited_by', 'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isManager(): bool      { return $this->role === 'manager'; }
    public function isCashier(): bool      { return $this->role === 'cashier'; }
    public function isReceptionist(): bool { return $this->role === 'receptionist'; }
    public function isTrainer(): bool      { return $this->role === 'trainer'; }
}
