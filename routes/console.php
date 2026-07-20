<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Notification backend scheduling
// Make sure a cron entry runs `php artisan schedule:run` every minute
// (or run `php artisan schedule:work` during local development).
Schedule::command('goals:check-deadlines')->dailyAt('07:00');
Schedule::command('goals:monthly-reminder')->monthlyOn(1, '08:00');

Schedule::command('transactions:daily-reminder')->dailyAt('20:00');
