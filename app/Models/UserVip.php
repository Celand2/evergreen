<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vip_id',
        'amount_invested',
        'daily_gain',
        'duration_days',
        'started_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'amount_invested' => 'decimal:2',
        'daily_gain' => 'decimal:2',
        'started_at' => 'date',
        'expires_at' => 'date',
        'last_gain_credited_at' => 'datetime',
    ];
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vip()
    {
        return $this->belongsTo(Vip::class);
    }

    public function dailyGains()
    {
        return $this->hasMany(DailyGain::class);
    }

    // Helpers
    public function hasGainToday()
    {
        return $this->dailyGains()->whereDate('date', today())->exists();
    }
    public function isVipActive(): bool
    {
        return $this->userVips()->active()->exists();
    }
}
