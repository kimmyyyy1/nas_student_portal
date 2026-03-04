<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentSubmittedNotification extends Notification
{

    public $applicant;
    public $isResubmission;

    /**
     * Create a new notification instance.
     */
    public function __construct($applicant, $isResubmission = false)
    {
        $this->applicant = $applicant;
        $this->isResubmission = $isResubmission;
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
        $subject = $this->isResubmission 
            ? 'Enrollment Documents Resubmission Acknowledged' 
            : 'Enrollment Submission Confirmation';

        $fullName = trim($this->applicant->first_name . ' ' . $this->applicant->last_name);
            
        $mail = (new MailMessage)
                    ->subject('NASCENT SAS — ' . $subject)
                    ->greeting('Dear ' . $fullName . ',');

        if ($this->isResubmission) {
            $mail->line('This is to confirm that your revised enrollment documents have been successfully received by the Office of the Registrar.')
                 ->line('Your resubmitted documents are now under review. You will receive a notification once the verification process has been completed.');
        } else {
            $mail->line('This is to acknowledge that your enrollment application for the **NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars (NASCENT SAS)** has been successfully submitted.')
                 ->line('Your enrollment form and supporting documents are now **under review by the Office of the Registrar**. You will be notified via email once the verification process has been completed.');
        }

        $mail->line('Should you need to review or track the status of your application, you may access your Applicant Portal through the link below.')
             ->action('Access Applicant Portal', route('applicant.dashboard'))
             ->line('For any concerns or inquiries, please do not hesitate to contact the NAS Office of the Registrar.')
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
