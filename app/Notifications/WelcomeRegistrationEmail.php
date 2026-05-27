<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeRegistrationEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Barangay SIS')
            ->greeting('Welcome to Barangay SIS!')
            ->line('Thank you for registering in the Barangay Bacani online portal.')
            ->line('Your account has been created successfully. You may now log in using your registered Gmail address and password.')
            ->line('Inside the portal, you can submit your resident identification, request barangay documents, and view updates from the barangay office.')
            ->line('Thank you for joining Barangay SIS.');
    }
}
