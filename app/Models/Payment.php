<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'appointment_id', 'provider', 'provider_payment_id', 'preference_id',
        'amount_cop', 'status', 'raw_payload', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'raw_payload' => 'array',
            'paid_at' => 'datetime',
            'amount_cop' => 'integer',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
