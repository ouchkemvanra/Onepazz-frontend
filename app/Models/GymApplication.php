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
    ];

    protected $casts = [
        'activity_types' => 'array',
        'reviewed_at'    => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
