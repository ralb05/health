<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $fillable = [
        'user_id', 'specialty_id', 'full_name', 'title', 'bio',
        'photo_url', 'experience_years', 'rating', 'reviews_count',
        'price_cop', 'tags', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'rating' => 'decimal:1',
            'is_active' => 'boolean',
        ];
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function activeSchedules(): HasMany
    {
        return $this->schedules()->where('is_active', true);
    }

    /** Precio formateado en pesos colombianos, ej. "$120.000 COP". */
    public function getPriceFormattedAttribute(): string
    {
        return '$'.number_format($this->price_cop, 0, ',', '.').' COP';
    }
}
