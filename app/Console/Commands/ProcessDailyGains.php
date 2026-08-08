<?php

namespace App\Console\Commands;

use App\Services\DailyGainService;
use Illuminate\Console\Command;

class ProcessDailyGains extends Command
{
    protected $signature = 'daily:process-gains';
    protected $description = 'Process daily gains for all active VIPs';

    public function handle(DailyGainService $dailyGainService): int
    {
        $processed = $dailyGainService->process();
        $this->info("Daily gains processed successfully: {$processed} credited.");

        return self::SUCCESS;
    }
}