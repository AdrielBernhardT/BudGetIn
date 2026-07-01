<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GoalMissedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Goal $goal)
    {
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
        $target = number_format((float) $this->goal->target_amount, 0, ',', '.');
        $current = number_format($this->goal->currentAmount(), 0, ',', '.');
        $percent = $this->goal->progressPercent();

        return (new MailMessage)
            ->subject("Goal '{$this->goal->name}' Not Yet Reached Before Deadline")
            ->greeting('Hi, ' . ($notifiable->fname ?? $notifiable->name))
            ->line("Deadline for goal \"{$this->goal->name}\" has passed, and the target has not been reached.")
            ->line("Last Progress: Rp {$current} from Rp {$target} ({$percent}%).")
            ->action('Review Goal', route('investment.index'))
            ->line('You can continue saving or reset the deadline for this goal.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $percent = $this->goal->progressPercent();

        return [
            'category' => 'goal_missed',
            'title' => 'Goal Not Yet Reached',
            'message' => "Deadline for goal \"{$this->goal->name}\" has passed. Only {$percent}% reached.",
            'icon' => 'alert-triangle',
            'url' => route('investment.index'),
            'goal_id' => $this->goal->id,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Goal Not Yet Reached')
            ->icon('/images/logo/logo-icon.png')
            ->body("Deadline for goal \"{$this->goal->name}\" has passed without reaching the target.")
            ->action('Review Goal', 'view_goal')
            ->data(['url' => route('investment.index')])
            ->options(['TTL' => 3600]);
    }
}
