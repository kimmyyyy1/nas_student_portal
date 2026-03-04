<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationsBell extends Component
{
    public $notificationCount = 0;
    public $lastNotificationId = null;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->notificationCount = $user->unreadNotifications()->count();
            $latest = $user->unreadNotifications()->latest()->first();
            $this->lastNotificationId = $latest ? $latest->id : null;
        }
    }

    public function refreshNotifications()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            $this->notificationCount = $user->unreadNotifications()->count();

            $latest = $user->unreadNotifications()->latest()->first();
            $currentLatestId = $latest ? $latest->id : null;

            // Only ding if there's a genuinely NEW notification (different ID)
            if ($currentLatestId && $currentLatestId !== $this->lastNotificationId) {
                $this->lastNotificationId = $currentLatestId;
                $this->dispatch('play-ding');
            }
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

                if ($applicantId) {
                    // Mark as read para sa LAHAT ng users na may ganitong notification (Syncing)
                    DB::table('notifications')
                        ->whereNull('read_at')
                        ->where(function($q) use ($applicantId) {
                            $q->where('data', 'like', '%"applicant_id":' . $applicantId . '%')
                              ->orWhere('data', 'like', '%"applicant_id":"' . $applicantId . '"%');
                        })
                        ->update(['read_at' => now()]);
                } else {
                    // Fallback
                    $notification->markAsRead();
                }

                // I-update ang local count agad
                $this->refreshNotifications();

                // Redirect
                if ($applicantId) {
                    $message = $data['message'] ?? '';
                    if (str_starts_with($message, 'Enrollment form submitted') || str_starts_with($message, 'New application from')) {
                        // Check if it's enrollment to redirect to the correct verification page
                        if (str_starts_with($message, 'Enrollment form submitted')) {
                            return redirect()->route('official-enrollment.show', ['id' => $applicantId]);
                        }
                    }
                    return redirect()->route('admission.show', ['id' => $applicantId]);
                }
            }
        }
    }
    
    public function markAllAsRead()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if (in_array($user->role, ['admin', 'registrar'])) {
                $adminIds = \App\Models\User::whereIn('role', ['admin', 'registrar'])->pluck('id');
                DB::table('notifications')
                    ->whereIn('notifiable_id', $adminIds)
                    ->where('notifiable_type', \App\Models\User::class)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            } else {
                // Mark all unread notifications as read
                $user->unreadNotifications()->update(['read_at' => now()]);
            }
            
            // Sync the counts
            $this->notificationCount = 0;
            $this->lastNotificationId = null;
            
            $this->dispatch('notifications-cleared');
        }
    }

    public function clearAll()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Delete all notifications strictly for this user from the database
            $user->notifications()->delete();
            
            // Sync the counts
            $this->notificationCount = 0;
            $this->lastNotificationId = null;
            
            $this->dispatch('notifications-cleared');
        }
    }

    public function render()
    {
        $notifications = collect();
        if (Auth::check()) {
            // Render BOTH read and unread notifications for history (up to 15)
            $notifications = Auth::user()->notifications()->latest()->take(15)->get();
        }

        return view('livewire.notifications-bell', [
            'notifications' => $notifications
        ]);
    }
}
