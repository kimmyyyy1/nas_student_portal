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
    public $fileName;   // e.g., 'PSA Birth Certificate'
    public $context;    // e.g., 'Admission Requirements' or 'Enrollment'

    public function __construct(Applicant $applicant, $type = 'new', $fileName = 'documents', $context = 'Admission')
    {
        $this->applicant = $applicant;
        $this->type = $type;
        $this->fileName = str_replace('_', ' ', ucwords($fileName)); // Format file name
        $this->context = $context;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $action = $this->type === 'resubmission' ? 'resubmitted' : 'submitted';
        
        // Example: "Kim Medrano resubmitted PSA Birth Certificate for Admission."
        $message = "has {$action} {$this->fileName} for {$this->context}.";

        // Determine correct link based on context
        $routeName = $this->context === 'Enrollment' ? 'official-enrollment.show' : 'admission.show';

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