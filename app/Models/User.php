<?php

namespace App\Models;

use App\Models\ExchangeRate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'country',
        'avatar',
        'password',
        'role',
        'status',
        'referral_code',
        'referred_by',
        'balance_investissable',
        'balance_retirable',
          'currency',  
        'preferred_payment_method_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'balance_investissable' => 'decimal:2',
        'balance_retirable' => 'decimal:2',
    ];

    // Auth par téléphone
    public function getAuthIdentifierName()
    {
        return 'phone';
    }

    // Relations
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function userVips()
    {
        return $this->hasMany(UserVip::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function dailyGains()
    {
        return $this->hasMany(DailyGain::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function preferredPaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'preferred_payment_method_id');
    }

    // Helpers
    public function hasCheckedInToday()
    {
        return $this->checkIns()->whereDate('date', today())->exists();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function toLocal(float $usd): string
    {
        if (!$this->currency) {
            return '$' . number_format($usd, 2) . ' USD';
        }

        $rate = ExchangeRate::where('currency', $this->currency)
            ->where('is_active', true)
            ->latest()
            ->value('rate_to_usd');

        if (!$rate || $rate == 0) {
            return '$' . number_format($usd, 2) . ' USD';
        }

        $local = $usd * $rate;
        return number_format($local, 2) . ' ' . $this->currency;
    }
}
