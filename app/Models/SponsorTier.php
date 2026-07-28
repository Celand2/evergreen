<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorTier extends Model
{
    protected $fillable = [
        'name', 'badge_emoji', 'min_actives',
        'bonus_usd', 'commission_l1', 'commission_l2', 'commission_l3', 'order',
    ];

    protected $casts = [
        'bonus_usd'     => 'decimal:2',
        'commission_l1' => 'decimal:2',
        'commission_l2' => 'decimal:2',
        'commission_l3' => 'decimal:2',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function achievements()
    {
        return $this->hasMany(SponsorTierAchievement::class);
    }

    // Trouve le tier correspondant à un nombre de filleuls actifs
    public static function forActiveCount(int $activeCount): self
    {
        return self::where('min_actives', '<=', $activeCount)
            ->orderByDesc('min_actives')
            ->first() ?? self::orderBy('min_actives')->firstOrFail();
    }

    // Prochain palier à atteindre
    public function nextTier(): ?self
    {
        return self::where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }
}