<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymApplication extends Model
{
    protected $fillable = [
        'studio_name', 'studio_name_kh', 'contact_name', 'contact_email',
        'contact_phone', 'address', 'district', 'city',
        'activity_types', 'description', 'website',
        'status', 'reviewed_at', 'reviewed_by', 'rejection_reason', 'notes',
        'invite_token', 'invite_sent_at', 'invite_expires_at', 'invited_by', 'source',
    ];

    protected $casts = [
        'activity_types'    => 'array',
        'reviewed_at'       => 'datetime',
        'invite_sent_at'    => 'datetime',
        'invite_expires_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isInviteExpired(): bool
    {
        return $this->invite_expires_at && $this->invite_expires_at->isPast();
    }
}
