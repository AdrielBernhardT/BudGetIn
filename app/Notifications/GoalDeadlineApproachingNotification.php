<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GoalDeadlineApproachingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Goal $goal, public int $daysLeft)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class, 'mail'];
    }

    protected function dayLabel(): string
    {
        return $this->daysLeft <= 0 ? 'hari ini' : "{$this->daysLeft} hari lagi";
    }

    public function toMail(object $notifiable): MailMessage
    {
        $target = number_format((float) $this->goal->target_amount, 0, ',', '.');
        $current = number_format($this->goal->currentAmount(), 0, ',', '.');
        $percent = $this->goal->progressPercent();

        return (new MailMessage)
            ->subject("Goal '{$this->goal->name}' Deadline {$this->dayLabel()}")
            ->greeting('Hi, ' . ($notifiable->fname ?? $notifiable->name))
            ->line("Deadline for goal \"{$this->goal->name}\" is {$this->dayLabel()}.")
            ->line("Progress you have made so far: Rp {$current} from Rp {$target} ({$percent}%).")
            ->action('Add Investment', route('investment.index'))
            ->line('Let\'s reach your target before the deadline!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $percent = $this->goal->progressPercent();

        return [
            'category' => 'goal_deadline_approaching',
            'title' => 'Goal Deadline Approaching',
            'message' => "Goal \"{$this->goal->name}\" deadline is {$this->dayLabel()}. Progress: {$percent}%.",
            'icon' => 'alarm-clock',
            'url' => route('investment.index'),
            'goal_id' => $this->goal->id,
            'days_left' => $this->daysLeft,
        ];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Goal Deadline Approaching ⏰')
            ->icon('/images/logo/logo-icon.png')
            ->body("Goal \"{$this->goal->name}\" deadline is {$this->dayLabel()}.")
            ->action('View Goal', 'view_goal')
            ->data(['url' => route('investment.index')])
            ->options(['TTL' => 3600]);
    }
}
