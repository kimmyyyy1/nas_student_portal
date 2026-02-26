<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Applicant;

class NewApplicantNotification extends Notification
{
    use Queueable;

    private $applicant;
    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Applicant $applicant, string $message)
    {
        $this->applicant = $applicant;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'applicant_id' => $this->applicant->id,
            'message' => $this->message,
        ];
    }
}
