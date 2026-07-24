<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorTierAchievement extends Model
{
    protected $fillable = ['user_id', 'sponsor_tier_id', 'bonus_usd', 'achieved_at'];

    protected $casts = [
        'bonus_usd'   => 'decimal:2',
        'achieved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sponsorTier()
    {
        return $this->belongsTo(SponsorTier::class);
    }
}