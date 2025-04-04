<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PedidoEmailNotification extends Notification
{
    use Queueable;

    private $linhas;

    /**
     * Create a new notification instance.
     */
    public function __construct($linhas)
    {
        $this->linhas = $linhas;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      //  dd($notifiable);
        return (new MailMessage)
                ->line($this->linhas[0])
                ->line($this->linhas[1])
                ->line($this->linhas[2]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
