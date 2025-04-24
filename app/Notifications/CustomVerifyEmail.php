<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email Anda - Lokapustaka')
            ->greeting('Hai, ' . $notifiable->name . ' 👋')
            ->line('Selamat datang di Lokapustaka! Kami senang kamu bergabung 🙌')
            ->line('Untuk mulai menggunakan akunmu dan menikmati koleksi buku digital kami, silakan verifikasi email kamu dengan menekan tombol di bawah ini.')
            ->action('Verifikasi Sekarang', $verificationUrl)
            ->line('Tautan ini akan kedaluwarsa dalam 30 menit demi keamanan akunmu.')
            ->line('Jika kamu tidak merasa membuat akun di Lokapustaka, kamu bisa abaikan email ini.')
            ->salutation('Salam hangat,  
    Tim Lokapustaka 📚');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(30),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())],
        );
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
