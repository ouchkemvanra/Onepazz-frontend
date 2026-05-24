<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'employer_id', 'subscription_id',
        'billing_period_start', 'billing_period_end',
        'employee_count', 'plan_price_usd', 'subtotal_usd',
        'tax_usd', 'total_usd', 'khr_rate', 'total_khr',
        'status', 'due_date', 'notes',
    ];

    protected $casts = [
        'billing_period_start' => 'date',
        'billing_period_end'   => 'date',
        'due_date'             => 'date',
        'total_usd'            => 'decimal:2',
        'total_khr'            => 'decimal:2',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(EmployerSubscription::class, 'subscription_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date?->isPast();
    }
}
