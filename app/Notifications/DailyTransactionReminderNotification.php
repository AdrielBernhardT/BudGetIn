<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DailyTransactionReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class, 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable->fname ?: $notifiable->name;

        return (new MailMessage)
            ->subject('Have you already recorded your transactions today? 📒')
            ->greeting("Hi, {$name}!")
            ->line('Don\'t forget to record your income or expenses today.')
            ->line('Regularly recording transactions helps you better understand your financial patterns.')
            ->action('Record Now', route('expense.index'))
            ->line('Keep up the great work in managing your finances!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'category' => 'daily_transaction_reminder',
            'title'    => 'Record Transactions Today 📒',
            'message'  => 'Don\'t forget to record your income or expenses today.',
            'icon'     => 'notebook',
            'url'      => route('expense.index'),
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Record Transactions Today 📒')
            ->icon('/images/logo/logo-icon.png')
            ->body('Don\'t forget to record your income or expenses today.')
            ->action('Record Now', 'record_transaction')
            ->data(['url' => route('expense.index')])
            ->options(['TTL' => 3600]);
    }
}
