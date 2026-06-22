<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Notifications\TransactionRecordedNotification;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * Only fires for income/expense (not transfer), matching the
     * "reminder catatan pengeluaran/pemasukan harian" requirement —
     * sent immediately the moment a transaction is recorded.
     */
    public function created(Transaction $transaction): void
    {
        if (!in_array($transaction->type, ['income', 'expense'], true)) {
            return;
        }

        $transaction->user?->notify(new TransactionRecordedNotification($transaction));
    }
}
