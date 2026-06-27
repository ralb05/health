<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** Para usar el slug en las URLs (route model binding). */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    /** Solo especialistas activos. */
    public function activeDoctors(): HasMany
    {
        return $this->doctors()->where('is_active', true);
    }
}
