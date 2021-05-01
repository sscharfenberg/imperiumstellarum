<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GameOver extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string $gameNumber
     */
    protected $gameNumber;

    /**
     * @var string $empire
     */
    protected $empire;

    /**
     * Create a new notification instance.
     * @param int $gameNumber
     * @param string $empire
     * @return void
     */
    public function __construct(int $gameNumber, string $empire)
    {
        $this->gameNumber = $gameNumber;
        $this->empire = $empire;
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
            ->subject(__('game.mail.gameOver.subject'))
            ->line(__('game.mail.gameOver.important', ['empire' => $this->empire, 'game' => $this->gameNumber]))
            ->line(__('game.mail.gameOver.login'))
            ->line(__('game.mail.gameOver.next'));
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
