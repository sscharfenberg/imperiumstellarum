<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSuspended extends Notification
{
    use Queueable;

    /**
     * @var string $duration
     */
    protected $duration;
    /**
     * @var string $until
     */
    protected $until;
    /**
     * @var string $reason
     */
    protected $reason;

    /**
     * Create a new notification instance.
     * @param string $duration
     * @param string $until
     * @param string $reason
     * @return void
     */
    public function __construct(string $duration, string $until, string $reason)
    {
        $this->duration = $duration;
        $this->until = $until;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('admin.user.suspensionNotification.subject'))
            ->line(
                $this->duration === "99"
                    ? __('admin.user.suspensionNotification.introductionForever')
                    : __('admin.user.suspensionNotification.introduction', ['days' => $this->duration])
            )
            ->line(__('admin.user.suspensionNotification.reason', ['reason' => $this->reason]))
            ->line(__('admin.user.suspensionNotification.appeal'))
            ->action(__('admin.user.suspensionNotification.action'), 'https://discuss.imperiumstellarum.io/index.php?/staff/')
            ->line(__('admin.user.suspensionNotification.until', [
                'until' => Carbon::parse($this->until)->format('d.m.Y H:i:s')
            ]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
