<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Applicant;

class ApplicantDocumentsSubmitted extends Notification
{
    use Queueable;

    public $applicant;
    public $type;       // 'new' or 'resubmission'
    public $messageBody; // Dito natin ilalagay ang summary ng files
    public $context;    // 'Admission Requirements' or 'Official Enrollment'

    // Binago natin ang constructor para tumanggap ng messageBody
    public function __construct(Applicant $applicant, $type = 'new', $messageBody = 'documents', $context = 'Admission')
    {
        $this->applicant = $applicant;
        $this->type = $type;
        $this->messageBody = $messageBody;
        $this->context = $context;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $action = $this->type === 'resubmission' ? 'resubmitted' : 'submitted';
        
        // Output example: "Kim Medrano has submitted PSA Birth Cert, Report Card for Admission."
        $message = "has {$action} {$this->messageBody} for {$this->context}.";

        $routeName = $this->context === 'Official Enrollment' ? 'official-enrollment.show' : 'admission.show';

        return [
            'applicant_id' => $this->applicant->id,
            'name' => $this->applicant->first_name . ' ' . $this->applicant->last_name,
            'message' => $message,
            'link' => route($routeName, $this->applicant->id),
            'type' => $this->type,
            'time' => now()
        ];
    }
}