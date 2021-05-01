<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GameFinished extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var int $gameNumber
     */
    protected $gameNumber;

    /**
     * @var int $turn
     */
    protected $turn;

    /**
     * @var string $winner
     */
    protected $winner;

    /**
     * Create a new notification instance.
     * @param int $gameNumber
     * @param int $turn
     * @param string $winner
     * @return void
     */
    public function __construct(int $gameNumber, int $turn, string $winner)
    {
        $this->gameNumber = $gameNumber;
        $this->turn = $turn;
        $this->winner = $winner;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('game.mail.gameFinished.subject', ['game' => $this->gameNumber]))
            ->line(__('game.mail.gameFinished.important', ['game' => $this->gameNumber, 'winner' => $this->winner, 'turn' => $this->turn]))
            ->line(__('game.mail.gameFinished.login'))
            ->line(__('game.mail.gameFinished.next'));
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
