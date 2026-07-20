<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'amount',
        'amount_usd',
        'amount_local',
        'currency',
        'rate_used',
        'status',
        'proof',
        'approved_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'amount_usd'   => 'decimal:2',
        'amount_local' => 'decimal:2',
        'rate_used'    => 'decimal:6',
        'approved_at'  => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}