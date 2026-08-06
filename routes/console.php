<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily gains processing at midnight
Schedule::command('daily:process-gains')
    ->everyMinute()
    ->timezone('Africa/Lusaka')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/schedule.log'));