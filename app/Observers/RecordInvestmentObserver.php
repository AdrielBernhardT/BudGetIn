<?php

namespace App\Observers;

use App\Models\Goal;
use App\Models\RecordInvestment;
use App\Notifications\GoalReachedNotification;

class RecordInvestmentObserver
{
    /**
     * Handle the RecordInvestment "created" event.
     *
     * Re-checks the related goal's progress; if the target has just been
     * reached and we haven't notified yet, send the "Goal Reached" notification.
     */
    public function created(RecordInvestment $record): void
    {
        $goal = Goal::with(['investments.records', 'user'])->find($record->goal_id);

        if (!$goal || $goal->reached_notified_at || !$goal->isReached()) {
            return;
        }
        
        $claimed = Goal::whereKey($goal->id)
            ->whereNull('reached_notified_at')
            ->update(['reached_notified_at' => now()]);

        if ($claimed) {
            $goal->user?->notify(new GoalReachedNotification($goal->withoutRelations()));
        }
    }
}
