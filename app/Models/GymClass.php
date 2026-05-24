<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    protected $fillable = [
        'gym_id', 'name', 'name_kh', 'description', 'trainer_name',
        'class_type', 'day_of_week', 'start_time', 'duration_minutes',
        'max_capacity', 'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'array',
        'is_active'   => 'boolean',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'class_id');
    }

    public function isToday(): bool
    {
        $todayNum = (int) now()->format('N') % 7;
        return in_array($todayNum, (array) ($this->day_of_week ?? []));
    }
}
