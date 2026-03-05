<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RecordFinalizedNotification extends Notification
{
    use Queueable;

    public $message;
    public $studentId;
    public $action; // 'finalized' or 'unfinalized'

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $studentId, $action)
    {
        $this->message = $message;
        $this->studentId = $studentId;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // save to database for realtime bell
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'student_id' => $this->studentId,
            'action' => $this->action,
        ];
    }
}
