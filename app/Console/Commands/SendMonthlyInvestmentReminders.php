<?php

namespace App\Console\Commands;

use App\Models\Goal;
use App\Notifications\MonthlyInvestmentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendMonthlyInvestmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:monthly-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a start-of-month reminder to users with active investment goals';

    public function handle(): int
    {
        $startOfMonth = Carbon::today()->startOfMonth();

        $goals = Goal::with(['user', 'investments.records'])
            ->whereNull('reached_notified_at')
            ->where(function ($query) use ($startOfMonth) {
                $query->whereNull('last_monthly_reminder_at')
                    ->orWhere('last_monthly_reminder_at', '<', $startOfMonth->toDateString());
            })
            ->get()
            ->reject(fn (Goal $goal) => $goal->isReached())
            ->groupBy('user_id');

        foreach ($goals as $userGoals) {
            $user = $userGoals->first()?->user;

            if (!$user) {
                continue;
            }

            $user->notify(new MonthlyInvestmentReminderNotification($userGoals));

            foreach ($userGoals as $goal) {
                $goal->forceFill(['last_monthly_reminder_at' => $startOfMonth->toDateString()])->save();
            }
        }

        $this->info("Sent monthly reminder to {$goals->count()} user(s) covering " . $goals->flatten()->count() . ' goal(s).');

        return self::SUCCESS;
    }
}
