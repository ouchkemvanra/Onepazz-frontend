<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'employer_id', 'amount_usd', 'amount_khr',
        'transfer_reference', 'transfer_date', 'bank_name',
        'receipt_path', 'status', 'confirmed_at', 'confirmed_by',
        'rejection_reason', 'notes',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'confirmed_at'  => 'datetime',
        'amount_usd'    => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function receiptUrl(): ?string
    {
        return $this->receipt_path ? Storage::url($this->receipt_path) : null;
    }
}
