<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $code,
        private string $type = 'login',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->type === 'login' 
            ? 'Your Admin Login OTP'
            : 'Your Admin Registration OTP';

        $message = $this->type === 'login'
            ? 'Use this code to log in to your admin account:'
            : 'Use this code to complete your admin registration:';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line($message)
            ->line('')
            ->line('**' . $this->code . '**')
            ->line('')
            ->line('This code expires in 10 minutes.')
            ->line('If you did not request this code, please ignore this email.')
            ->action('Go to Admin Panel', route('admin.login'))
            ->line('Thank you for using Cranelinks!');
    }
}
