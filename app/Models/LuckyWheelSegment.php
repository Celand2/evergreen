<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyWheelSegment extends Model
{
    protected $fillable = ['amount_usd', 'is_active'];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function spins()
    {
        return $this->hasMany(LuckyWheelSpin::class);
    }
}