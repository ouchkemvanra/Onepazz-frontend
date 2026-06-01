<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerInvitation extends Model
{
    protected $fillable = [
        'invite_token', 'contact_name', 'contact_email', 'company_name',
        'suggested_plan_id', 'personal_message', 'invited_by',
        'invite_sent_at', 'invite_expires_at', 'status', 'accepted_at',
    ];

    protected $casts = [
        'invite_sent_at'   => 'datetime',
        'invite_expires_at'=> 'datetime',
        'accepted_at'      => 'datetime',
    ];

    public function suggestedPlan()
    {
        return $this->belongsTo(Plan::class, 'suggested_plan_id');
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->invite_expires_at->isPast();
    }
}
