<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    // Estados posibles de una cita
    public const STATUS_PENDING = 'pending_payment';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    /** Estados que mantienen el cupo reservado (ocupan agenda). */
    public const ACTIVE_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED];

    protected $fillable = [
        'patient_id', 'doctor_id', 'specialty_id', 'starts_at', 'ends_at',
        'type', 'status', 'price_cop', 'meeting_url', 'notes', 'expires_at', 'reminded_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'expires_at' => 'datetime',
            'reminded_at' => 'datetime',
            'price_cop' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // Mantiene reservation_key sincronizada: con valor mientras la cita está
        // activa (reserva el cupo) y null cuando se cancela/expira (lo libera).
        static::saving(function (Appointment $appointment) {
            $appointment->reservation_key = in_array($appointment->status, self::ACTIVE_STATUSES, true)
                ? $appointment->doctor_id.':'.$appointment->starts_at->format('YmdHis')
                : null;
        });
    }

    // Relaciones
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Helpers
    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    public function getIsExpiredHoldAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING
            && $this->expires_at !== null
            && $this->expires_at->isPast();
    }

    public function getPriceFormattedAttribute(): string
    {
        return '$'.number_format($this->price_cop, 0, ',', '.').' COP';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pendiente de pago',
            self::STATUS_CONFIRMED => 'Confirmada',
            self::STATUS_COMPLETED => 'Completada',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_EXPIRED => 'Expirada',
            default => $this->status,
        };
    }
}
