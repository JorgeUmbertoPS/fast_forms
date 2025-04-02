<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    protected string $token;
    protected string $url;

    public function __construct(string $token, string $url)
    {
        $this->token = $token;
        $this->url = $url; // Armazena a URL recebida
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Recupere sua senha')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Você solicitou a redefinição de sua senha.')
            ->action('Redefinir Senha', $this->url) // Usa a URL correta
            ->line('Se você não solicitou essa alteração, ignore este e-mail.')
            ->salutation('Atenciosamente, Equipe Fast Forms');
    }
}