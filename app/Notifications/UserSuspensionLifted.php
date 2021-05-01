<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSuspensionLifted extends Notification implements ShouldQueue
{
    use Queueable;

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
     * @param string $until
     * @param string $reason
     * @return void
     */
    public function __construct(string $until, string $reason)
    {
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
            ->subject(__('admin.user.supensionLiftedNotification.subject'))
            ->line( __('admin.user.supensionLiftedNotification.introduction', [
                'until' => Carbon::parse($this->until)->format('d.m.Y H:i:s')
            ]))
            ->line(__('admin.user.supensionLiftedNotification.reason', ['reason' => $this->reason]))
            ->line(__('admin.user.supensionLiftedNotification.result'));
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
