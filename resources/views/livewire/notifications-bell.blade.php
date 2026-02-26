<div class="relative" 
     x-data="{ 
         open: false, 
         dingAudio: null,
         init() {
             this.dingAudio = new Audio('{{ asset('sounds/ding.mp3') }}');
             this.dingAudio.preload = 'auto';
             this.dingAudio.volume = 1.0;
         },
         playDing() {
             if (this.dingAudio) {
                 this.dingAudio.currentTime = 0;
                 this.dingAudio.play().catch(() => {});
             }
         }
     }" 
     x-on:play-ding.window="playDing()"
     x-on:notifications-cleared.window="open = false"
     wire:key="notif-bell-root">
    
    <div wire:poll.1s="refreshNotifications"></div>

    {{-- Bell Button --}}
    <button type="button"
            @click.stop="open = !open" 
            class="group relative flex items-center justify-center w-12 h-12 rounded-2xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform active:scale-90 border border-gray-100 z-[60]">
        
        <i class='bx bxs-bell text-2xl transition-colors duration-300 {{ $notificationCount > 0 ? 'text-blue-600 animate-swing' : 'text-gray-400 group-hover:text-blue-500' }}'></i>
        
        @if($notificationCount > 0)
            <span class="absolute -top-1 -right-1 flex h-5 w-5" wire:key="badge-{{ $notificationCount }}">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-[10px] font-bold text-white items-center justify-center ring-2 ring-white">
                    {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                </span>
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" 
         @click.away="open = false" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute right-0 bottom-full mb-4 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden z-[1000]">
        
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">Notifications</h3>
            @if($notificationCount > 0)
                <button wire:click.prevent="markAllAsRead" 
                        type="button"
                        class="text-[10px] uppercase tracking-wider font-bold px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all active:scale-95">
                    Mark all as read
                </button>
            @endif
        </div>

        <div class="max-h-[400px] overflow-y-auto custom-scroll divide-y divide-gray-50 bg-white">
            @forelse($notifications as $notification)
                <a href="{{ route('admission.show', ['id' => $notification->data['applicant_id'] ?? '#']) }}" 
                   wire:click.prevent="markAsRead('{{ $notification->id }}')"
                   wire:key="notif-{{ $notification->id }}"
                   class="group flex items-start gap-4 px-6 py-4 hover:bg-blue-50 transition-all duration-200 decoration-none">
                    
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <i class='bx bxs-user-detail text-xl'></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 leading-tight mb-1 group-hover:text-blue-700 transition-colors">
                            {{ $notification->data['message'] ?? 'New Application' }}
                        </p>
                        <div class="flex items-center gap-2 text-[11px] text-gray-500">
                            <i class='bx bx-time-five'></i>
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex-shrink-0 self-center">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    </div>
                </a>
            @empty
                <div class="py-12 flex flex-col items-center justify-center text-gray-400 gap-3">
                    <i class='bx bx-notification-off text-3xl'></i>
                    <p class="text-sm font-medium">No unread notifications</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        @keyframes swing {
            0%, 100% { transform: rotate(0deg); }
            20% { transform: rotate(10deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(5deg); }
            80% { transform: rotate(-5deg); }
        }
        .animate-swing { animation: swing 2s infinite; transform-origin: top center; }
        [x-cloak] { display: none !important; }
    </style>
</div>
