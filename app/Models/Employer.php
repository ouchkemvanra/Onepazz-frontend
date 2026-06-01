<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_name', 'company_name_kh', 'registration_number', 'industry',
        'company_size', 'logo_url', 'website', 'address_line1', 'address_line2',
        'city', 'province', 'contact_name', 'contact_email', 'contact_phone',
        'admin_user_id', 'status', 'notes', 'reference_code', 'source',
    ];

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(EmployerSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(EmployerSubscription::class)->where('status', 'active')->latest();
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function monthlyTotalUsd(): float
    {
        $sub = $this->activeSubscription;
        if (!$sub) return 0;
        return $sub->employee_count * $sub->plan->price_usd;
    }
}
