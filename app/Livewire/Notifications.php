<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    protected $listeners = ['refreshNotifications' => '$refresh'];

    // 1. KUNIN ANG LAHAT (Read & Unread) para sa Listahan
    public function getNotificationsProperty()
    {
        // Kukunin ang latest 15 notifications (nabasa man o hindi)
        return Auth::user()->notifications()->latest()->take(15)->get();
    }

    // 2. KUNIN ANG BILANG NG UNREAD (Para sa Red Badge sa Bell)
    public function getUnreadCountProperty()
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function markAsRead($notificationId, $link)
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->to($link);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('refreshNotifications');
    }

    public function render()
    {
        $latest = Auth::user()->unreadNotifications()->latest()->first();
        $showAlert = false;

        if ($latest && $latest->created_at->diffInSeconds(now()) < 5) {
            $showAlert = true;
        }

        return view('livewire.notifications', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount, // Ipasa ang count
            'latest' => $latest,
            'showAlert' => $showAlert
        ]);
    }
}