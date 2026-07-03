<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Support\Carbon;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'icon',
        'target_amount',
        'target_date',
        'reached_notified_at',
        'deadline_notified_at',
        'missed_notified_at',
        'last_monthly_reminder_at',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'target_date' => 'date',
        'reached_notified_at' => 'datetime',
        'deadline_notified_at' => 'datetime',
        'missed_notified_at' => 'datetime',
        'last_monthly_reminder_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function investmentRecords()
    {
        return $this->hasMany(RecordInvestment::class);
    }

    /**
     * Total amount actually invested toward this goal so far.
     * Relies on `investments.records` being eager-loaded for performance;
     * falls back to a query if it isn't.
     */
    public function currentAmount(): float
    {
        if ($this->relationLoaded('investments')) {
            return (float) $this->investments->sum(function ($investment) {
                return $investment->relationLoaded('records')
                    ? $investment->records->sum('transaction_amount')
                    : $investment->records()->sum('transaction_amount');
            });
        }

        return (float) $this->investments()->with('records')->get()->sum(function ($investment) {
            return $investment->records->sum('transaction_amount');
        });
    }

    public function progressPercent(): float
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }

        return round(($this->currentAmount() / (float) $this->target_amount) * 100, 1);
    }

    public function isReached(): bool
    {
        return (float) $this->target_amount > 0
            && $this->currentAmount() >= (float) $this->target_amount;
    }

    /**
     * Days left until target_date. Positive = days remaining, negative = overdue.
     * Returns null if the goal has no deadline set.
     */
    public function daysUntilDeadline(): ?int
    {
        if (!$this->target_date) {
            return null;
        }

        return (int) Carbon::today()->diffInDays($this->target_date, false);
    }

    public function isOverdue(): bool
    {
        $days = $this->daysUntilDeadline();

        return $days !== null && $days < 0;
    }
}
