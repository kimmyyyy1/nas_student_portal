<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentStatusUpdatedNotification extends Notification
{

    public $applicant;
    public $status;
    public $remarks;

    /**
     * Create a new notification instance.
     */
    public function __construct($applicant, $status, $remarks = null)
    {
        $this->applicant = $applicant;
        $this->status = $status;
        $this->remarks = $remarks;
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
        $fullName = trim($this->applicant->first_name . ' ' . $this->applicant->last_name);

        $mail = (new MailMessage)
                    ->subject('NASCENT SAS — Enrollment Status Update')
                    ->greeting('Dear ' . $fullName . ',')
                    ->line('We are writing to inform you that your enrollment application status has been updated to: **' . $this->status . '**.');

        if ($this->remarks) {
            $mail->line('**Remarks from the Office of the Registrar:**')
                 ->line('> ' . $this->remarks);
        }

        if (str_contains($this->status, 'Returned')) {
            $mail->line('Kindly review the remarks indicated above and resubmit the required documents at your earliest convenience through your Applicant Portal.')
                 ->action('Update Enrollment Documents', route('applicant.dashboard'));
        } elseif ($this->status === 'Officially Enrolled') {
            $mail->line('**Congratulations!** You have been officially enrolled at the National Academy of Sports.')
                 ->line('Please log in to your Student Portal for your official class schedule and further instructions regarding the start of classes.')
                 ->action('Access Student Portal', route('applicant.dashboard'));
        } else {
             $mail->line('You may view the current status of your application by logging into your Applicant Portal.')
                  ->action('Access Applicant Portal', route('applicant.dashboard'));
        }

        $mail->line('For any concerns or inquiries, please do not hesitate to contact the NAS Office of the Registrar.')
             ->salutation('Respectfully yours,')
             ->line('**National Academy of Sports**')
             ->line('Office of the Registrar');

        return $mail;
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
