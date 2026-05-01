<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SentOtp extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $otp)
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your One-Time Password (OTP)')
                    ->line('Dear ' . $notifiable->name . ',')
                    ->line('Your One-Time Password (OTP) is: ' . $this->otp)
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'name' => $notifiable->name,
            'phone' => $notifiable->phone,
        ];
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
