<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DailyTransactionReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDailyTransactionReminder extends Command
{
    protected $signature = 'transactions:daily-reminder';

    protected $description = 'Remind users to record their transactions for today (default: 20:00)';

    public function handle(): int
    {
        $today = Carbon::today();

        User::whereNotNull('email_verified_at') // skip accounts that never finished OTP verification
            ->whereDoesntHave('transactions', function ($query) use ($today) {
                $query->whereDate('date', $today);
            })
            ->chunk(200, function ($users) {
                foreach ($users as $user) {
                    $user->notify(new DailyTransactionReminderNotification());
                }
            });

        $this->info("Daily reminder sent.");

        return self::SUCCESS;
    }
}
