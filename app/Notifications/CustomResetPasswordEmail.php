<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordEmail extends Notification
{
    use Queueable;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));

        return (new MailMessage)
            ->subject('Permintaan Reset Password - Lokapustaka')
            ->greeting('Hai, ' . $notifiable->name . ' 👋')
            ->line('Kami menerima permintaan untuk mereset password akun kamu di Lokapustaka.')
            ->line('Klik tombol di bawah ini untuk mengatur ulang password kamu:')
            ->action('Reset Password', $url)
            ->line('Link ini akan kedaluwarsa dalam 30 menit.')
            ->line('Jika kamu tidak meminta reset password, kamu bisa abaikan email ini.')
            ->salutation('Salam hangat,  
    Tim Lokapustaka 📚');
    }
}
