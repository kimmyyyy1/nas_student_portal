<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationsBell extends Component
{
    public $notificationCount = 0;
    public $lastCount = 0;

    public function mount()
    {
        $this->refreshNotifications();
        $this->lastCount = $this->notificationCount;
    }

    public function refreshNotifications()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Gamitin ang built-in relation para makuha ang bilang
            $this->notificationCount = $user->unreadNotifications()->count();

            if ($this->notificationCount > $this->lastCount) {
                $this->dispatch('play-ding');
            }
            $this->lastCount = $this->notificationCount;
        }
    }

    public function markAsRead($notificationId)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Hanapin ang specific notification gamit ang Eloquent
            $notification = $user->notifications()->where('id', $notificationId)->first();
            
            if ($notification) {
                $data = $notification->data;
                $applicantId = $data['applicant_id'] ?? null;

                // Mark as read gamit ang built-in method
                $notification->markAsRead();

                // I-update ang local count agad
                $this->refreshNotifications();

                // Redirect
                if ($applicantId) {
                    return redirect()->route('admission.show', ['id' => $applicantId]);
                }
            }
        }
    }
    
    public function markAllAsRead()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Mark all unread notifications as read
            $user->unreadNotifications()->update(['read_at' => now()]);
            
            // Sync the counts
            $this->notificationCount = 0;
            $this->lastCount = 0;
            
            $this->dispatch('notifications-cleared');
        }
    }

    public function render()
    {
        $notifications = collect();
        if (Auth::check()) {
            // Render unread notifications only
            $notifications = Auth::user()->unreadNotifications()->latest()->take(10)->get();
        }

        return view('livewire.notifications-bell', [
            'notifications' => $notifications
        ]);
    }
}
