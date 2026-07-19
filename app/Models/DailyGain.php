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
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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