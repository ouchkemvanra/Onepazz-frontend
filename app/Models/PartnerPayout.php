<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPayout extends Model
{
    protected $fillable = [
        'gym_id', 'year', 'month', 'checkins', 'units',
        'value_per_unit', 'payout_usd', 'onepazz_cut',
        'khr_rate', 'payout_khr', 'status', 'confirmed_at', 'confirmed_by',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function monthLabel(): string
    {
        return \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }
}
