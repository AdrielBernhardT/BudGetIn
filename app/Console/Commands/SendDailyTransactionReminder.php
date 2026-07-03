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

        User::whereDoesntHave('transactions', function ($query) use ($today) {
            $query->whereDate('date', $today);
        })->chunk(5, function ($users) {
            foreach ($users as $user) {
                $user->notify(new DailyTransactionReminderNotification());
            }
            sleep(3); // tunggu 3 detik tiap 5 user
        });

        $this->info("Daily reminder sent.");

        return self::SUCCESS;
    }
}
