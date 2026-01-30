<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;

class AdmissionAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $tempPassword; // Variable para sa password

    /**
     * Create a new message instance.
     * Tinatanggap nito ang student object at ang generated password.
     */
    public function __construct(Student $student, $tempPassword)
    {
        $this->student = $student;
        $this->tempPassword = $tempPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('NASCENT SAS Application Approved: Your Student Portal Credentials')
                    ->view('emails.admission_accepted');
    }
}