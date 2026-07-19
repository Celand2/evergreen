<?php

namespace App\Console\Commands;

use App\Models\UserVip;
use App\Models\DailyGain;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessDailyGains extends Command
{
    protected $signature = 'daily:process-gains';
    protected $description = 'Process daily gains for all active VIPs';

    public function handle(): void
    {
        $today = Carbon::today();
        
        // Get all active user_vips
        $activeUserVips = UserVip::active()->get();
        
        foreach ($activeUserVips as $userVip) {
            // Check if daily gain already exists for today
            $hasGainToday = DailyGain::where('user_vip_id', $userVip->id)
                ->whereDate('date', $today)
                ->exists();
            
            if (!$hasGainToday) {
                // Create daily gain
                $dailyGainAmount = $userVip->vip->calculateDailyGain($userVip->amount_invested);
                
                DailyGain::create([
                    'user_id' => $userVip->user_id,
                    'user_vip_id' => $userVip->id,
                    'amount' => $dailyGainAmount,
                    'date' => $today,
                ]);
                
                // Add to user's balance_retirable
                $user = $userVip->user;
                $user->balance_retirable += $dailyGainAmount;
                $user->save();
            }
        }
        
        // Expire user_vips where expires_at < today
        UserVip::where('status', 'active')
            ->where('expires_at', '<', $today)
            ->update(['status' => 'expired']);
        
        $this->info('Daily gains processed successfully.');
    }
}