<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TransactionRecordedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Transaction $transaction)
    {
    }

    /**
     * In-app only by design — an email/push per transaction would be too noisy.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $isIncome = $this->transaction->type === 'income';
        $amount = number_format((float) $this->transaction->amount, 0, ',', '.');

        return [
            'category' => 'transaction_recorded',
            'title' => $isIncome ? 'Income Recorded' : 'Expense Recorded',
            'message' => "{$this->transaction->title}: Rp {$amount}",
            'icon' => $isIncome ? 'trending-up' : 'trending-down',
            'url' => $isIncome ? route('income.index') : route('expense.index'),
            'transaction_id' => $this->transaction->id,
        ];
    }
}
