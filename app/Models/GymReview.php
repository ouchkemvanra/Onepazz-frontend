<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymReview extends Model
{
    protected $fillable = ['gym_id', 'user_id', 'rating', 'comment'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
