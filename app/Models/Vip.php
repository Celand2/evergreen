<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'daily_percentage',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'daily_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function userVips()
    {
        return $this->hasMany(UserVip::class);
    }

    // Calcul du gain journalier
    public function calculateDailyGain(float $amount): float
    {
        return $amount * ($this->daily_percentage / 100);
    }
}