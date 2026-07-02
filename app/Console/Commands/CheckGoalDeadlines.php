<?php

namespace App\Console\Commands;

use App\Models\Goal;
use App\Notifications\GoalDeadlineApproachingNotification;
use App\Notifications\GoalMissedNotification;
use App\Notifications\GoalReachedNotification;
use Illuminate\Console\Command;

class CheckGoalDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users when a goal is reached late, due within 7 days, or its deadline has passed without reaching the target';

    public function handle(): int
    {
        $goals = Goal::with(['user', 'investments.records'])
            ->whereNotNull('target_date')
            ->whereNull('reached_notified_at')
            ->get();

        $reached = 0;
        $approaching = 0;
        $missed = 0;

        foreach ($goals as $goal) {
            if ($goal->isReached()) {
                $goal->user?->notify(new GoalReachedNotification($goal->withoutRelations())); // <-- fix
                $goal->forceFill(['reached_notified_at' => now()])->save();
                $reached++;
                continue;
            }

            $daysLeft = $goal->daysUntilDeadline();

            if ($daysLeft < 0) {
                if (!$goal->missed_notified_at) {
                    $goal->user?->notify(new GoalMissedNotification($goal->withoutRelations())); // <-- fix
                    $goal->forceFill(['missed_notified_at' => now()])->save();
                    $missed++;
                }
                continue;
            }

            if ($daysLeft <= 7 && !$goal->deadline_notified_at) {
                $goal->user?->notify(new GoalDeadlineApproachingNotification($goal->withoutRelations(), $daysLeft)); // <-- fix
                $goal->forceFill(['deadline_notified_at' => now()])->save();
                $approaching++;
            }
        }

        $this->info("Checked {$goals->count()} goals — reached: {$reached}, approaching: {$approaching}, missed: {$missed}.");

        return self::SUCCESS;
    }
}
