<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'payment_method_id',
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
    public function toLocal(float $usd): string
    {
        if (!$this->currency) {
            return '$' . number_format($usd, 2) . ' USD';
        }

        $rate = \App\Models\ExchangeRate::where('currency', $this->currency)
            ->where('is_active', true)
            ->latest()
            ->value('rate_to_usd');

        if (!$rate) {
            return '$' . number_format($usd, 2) . ' USD';
        }

        $local = $usd * $rate;
        return number_format($local, 2) . ' ' . $this->currency;
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
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
