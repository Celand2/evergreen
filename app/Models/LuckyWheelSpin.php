<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyWheelSpin extends Model
{
    protected $fillable = ['user_id', 'lucky_wheel_segment_id', 'amount_usd'];

    protected $casts = [
        'amount_usd' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function segment()
    {
        return $this->belongsTo(LuckyWheelSegment::class, 'lucky_wheel_segment_id');
    }
}