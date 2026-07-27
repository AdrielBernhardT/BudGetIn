<?php

namespace App\Providers;

use App\Models\RecordInvestment;
use App\Models\Transaction;
use App\Observers\RecordInvestmentObserver;
use App\Observers\TransactionObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceHttps();
        }

        Transaction::observe(TransactionObserver::class);
        RecordInvestment::observe(RecordInvestmentObserver::class);

        RateLimiter::for('daily-reminder', function ($job) {
            return Limit::perSecond(5, 3);
        });
    }
}
