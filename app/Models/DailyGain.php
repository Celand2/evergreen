<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyGain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_vip_id',
        'amount',
        'amount_usd',
        'amount_local',
        'currency',
        'rate_used',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_usd' => 'decimal:2',
        'amount_local' => 'decimal:2',
        'rate_used' => 'decimal:6',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userVip()
    {
        return $this->belongsTo(UserVip::class);
    }
}
