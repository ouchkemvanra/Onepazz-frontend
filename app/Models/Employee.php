<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'employer_id', 'subscription_id',
        'department', 'job_title', 'employee_code',
        'membership_card_no', 'joined_date', 'status',
        'suspended_at', 'suspended_reason',
    ];

    protected $casts = [
        'joined_date'  => 'date',
        'suspended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(EmployerSubscription::class, 'subscription_id');
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function checkinsThisMonth(): int
    {
        return $this->checkins()
            ->whereMonth('checked_in_at', now()->month)
            ->whereYear('checked_in_at', now()->year)
            ->count();
    }

    public function hasReachedMonthlyLimit(): bool
    {
        $limit = $this->subscription?->plan?->gym_checkins_per_month ?? 10;
        return $this->checkinsThisMonth() >= $limit;
    }
}
