<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    protected $fillable = [
        'gym_class_id', 'user_id', 'status', 'booked_at', 'notified_at',
    ];

    protected $casts = [
        'booked_at'    => 'datetime',
        'notified_at'  => 'datetime',
    ];

    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'gym_class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
