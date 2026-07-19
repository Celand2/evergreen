<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'currency',
        'rate_to_usd',
        'date',
        'is_active',
    ];

    protected $casts = [
        'rate_to_usd' => 'decimal:6',
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Convertir USD → local
    public static function toLocal(float $usd, string $currency): float
    {
        $rate = self::where('currency', $currency)
                    ->where('is_active', true)
                    ->latest()
                    ->value('rate_to_usd');

        return $usd * $rate;
    }

    // Convertir local → USD
    public static function toUsd(float $local, string $currency): float
    {
        $rate = self::where('currency', $currency)
                    ->where('is_active', true)
                    ->latest()
                    ->value('rate_to_usd');

        return $local / $rate;
    }
}