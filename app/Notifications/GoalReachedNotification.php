<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GoalReachedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Goal $goal)
    {
        if (!$goal->exists) {
            throw new \InvalidArgumentException('Goal must be persisted before sending notification');
        }
        $this->goal = $goal;
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class, 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format((float) $this->goal->target_amount, 0, ',', '.');

        return (new MailMessage)
            ->subject("Goal '{$this->goal->name}' reached! 🎉")
            ->greeting('Congratulations, ' . ($notifiable->fname ?? $notifiable->name) . '!')
            ->line("Your goal \"{$this->goal->name}\" already reached Rp {$amount}.")
            ->line('Great job! Your consistency in saving/investing is paying off.')
            ->action('View Goal', route('investment.index'))
            ->line('It\'s time to set new goals and keep growing.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $amount = number_format((float) $this->goal->target_amount, 0, ',', '.');

        return [
            'category' => 'goal_reached',
            'title' => 'Goal Reached! 🎉',
            'message' => "Goal \"{$this->goal->name}\" already reached Rp {$amount}.",
            'icon' => 'trophy',
            'url' => route('investment.index'),
            'goal_id' => $this->goal->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Goal Reached! 🎉')
            ->icon('/images/logo/logo-icon.png')
            ->body("Goal \"{$this->goal->name}\" already reached its target.")
            ->action('View Goal', 'view_goal')
            ->data(['url' => route('investment.index')])
            ->options(['TTL' => 3600]);
    }
}
