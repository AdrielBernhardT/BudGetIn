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

        if (!$goal || $goal->reached_notified_at) {
            return;
        }

        if ($goal->isReached()) {
            $goal->user?->notify(new GoalReachedNotification($goal->withoutRelations())); // <-- fix
            $goal->forceFill(['reached_notified_at' => now()])->save();
        }
    }
}
