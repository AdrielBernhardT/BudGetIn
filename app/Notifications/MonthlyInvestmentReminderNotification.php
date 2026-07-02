<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class MonthlyInvestmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param Collection<int, \App\Models\Goal> $goals Active (not yet reached) goals for this user.
     */
    public function __construct(public Collection $goals)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class, 'mail'];
    }

    protected function monthLabel(): string
    {
        return now()->translatedFormat('F Y');
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Time to Save for This Month\'s Goals')
            ->greeting('Hi, ' . ($notifiable->fname ?? $notifiable->name))
            ->line('The month of ' . $this->monthLabel() . ' has started. Here are your active goals:');

        foreach ($this->goals as $goal) {
            $target = number_format((float) $goal->target_amount, 0, ',', '.');
            $percent = $goal->progressPercent();
            $mail->line("• {$goal->name} — progress {$percent}% from target Rp {$target}");
        }

        return $mail
            ->action('Record Investment', route('investment.index'))
            ->line('Don\'t forget to save for your goals this month!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'category' => 'monthly_investment_reminder',
            'title' => 'Time to Save for This Month\'s Goals',
            'message' => 'Don\'t forget to set aside money for your ' . $this->goals->count() . ' active goals this month.',
            'icon' => 'piggy-bank',
            'url' => route('investment.index'),
            'goal_ids' => $this->goals->pluck('id')->all(),
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Time to Save for This Month\'s Goals 💰')
            ->icon('/images/logo/logo-icon.png')
            ->body('Don\'t forget to set aside money for your ' . $this->goals->count() . ' active goals this month.')
            ->action('Record Investment', 'view_goal')
            ->data(['url' => route('investment.index')])
            ->options(['TTL' => 3600]);
    }
}
