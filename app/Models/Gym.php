<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gym extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'name_kh', 'slug', 'description', 'description_kh',
        'logo_url', 'cover_image_url', 'photo_urls',
        'address_line1', 'district', 'city', 'province',
        'latitude', 'longitude', 'phone', 'email', 'website',
        'activity_types', 'amenities', 'tier', 'operating_hours',
        'admin_user_id', 'status', 'partner_since',
        'average_rating', 'review_count',
    ];

    protected $casts = [
        'photo_urls'      => 'array',
        'activity_types'  => 'array',
        'amenities'       => 'array',
        'operating_hours' => 'array',
        'partner_since'   => 'date',
    ];

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function classes()
    {
        return $this->hasMany(GymClass::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function reviews()
    {
        return $this->hasMany(GymReview::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_gyms', 'gym_id', 'user_id');
    }

    public function refreshRating(): void
    {
        $this->update([
            'average_rating' => round($this->reviews()->avg('rating') ?? 0, 2),
            'review_count'   => $this->reviews()->count(),
        ]);
    }

    public function isOpenNow(): bool
    {
        if (!$this->operating_hours) return true;
        $dayMap = ['Sun' => 'sun', 'Mon' => 'mon', 'Tue' => 'tue', 'Wed' => 'wed', 'Thu' => 'thu', 'Fri' => 'fri', 'Sat' => 'sat'];
        $dayKey = $dayMap[now()->format('D')] ?? 'mon';
        $hours  = $this->operating_hours[$dayKey] ?? null;
        if (!$hours || ($hours['closed'] ?? false)) return false;
        $now = now()->format('H:i');
        return $now >= $hours['open'] && $now <= $hours['close'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)               { return $query->where('status', 'active'); }
    public function scopeByCity($query, string $city) { return $query->where('city', $city); }
    public function scopeByTier($query, string $tier) { return $query->where('tier', $tier); }
}
