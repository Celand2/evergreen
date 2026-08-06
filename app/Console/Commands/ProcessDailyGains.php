<?php

namespace App\Console\Commands;

use App\Services\DailyGainService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessDailyGains extends Command
{
    protected $signature = 'daily:process-gains {--date= : Date to process (YYYY-MM-DD)}';
    protected $description = 'Process daily gains for all active VIPs';

    public function handle(DailyGainService $dailyGainService): int
    {
        try {
            $date = $this->option('date')
                ? Carbon::createFromFormat('Y-m-d', $this->option('date'))->startOfDay()
                : Carbon::now();
        } catch (\Throwable) {
            $this->error('The --date option must use the YYYY-MM-DD format.');

            return self::FAILURE;
        }

        $processed = $dailyGainService->process($date);
        $this->info("Daily gains processed successfully: {$processed} credited.");

        return self::SUCCESS;
    }
}