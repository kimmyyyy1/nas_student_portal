<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    // Polling listener
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function render()
    {
        $user = Auth::user();

        // 1. Kunin ang Unread Count (Eloquent)
        // Ito ay magiging 0 pagkatapos mag-reload ng page galing sa route
        $unreadCount = $user->unreadNotifications()->count();

        // 2. Kunin ang Listahan
        $notifications = $user->notifications()->latest()->take(15)->get();

        // 3. Alert Logic (Kung may bago sa last 5 seconds)
        $latest = $user->notifications()->latest()->first();
        $showAlert = ($latest && is_null($latest->read_at) && $latest->created_at->diffInSeconds(now()) < 5);

        return view('livewire.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'latest' => $latest,
            'showAlert' => $showAlert
        ]);
    }
}