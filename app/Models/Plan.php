<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'name_kh', 'description', 'description_kh',
        'price_usd', 'tier', 'max_employees', 'gym_checkins_per_month',
        'features', 'is_active', 'display_order',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
        'price_usd' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(EmployerSubscription::class);
    }

    public function priceKhr(): float
    {
        $rate = (float) \DB::table('platform_config')->where('key', 'khr_rate')->value('value') ?: 4100;
        return round($this->price_usd * $rate);
    }
}
