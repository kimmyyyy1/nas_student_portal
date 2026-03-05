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

    {{-- Bell Button (Soft Light Accent) --}}
    <button type="button"
            @click.stop="open = !open" 
            class="group relative flex items-center justify-center w-11 h-11 rounded-2xl bg-white shadow-[0_4px_15px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_25px_rgba(59,130,246,0.15)] border border-slate-100 transition-all duration-300 transform active:scale-95 z-[60]">
        
        <!-- Soft background bloom on hover -->
        <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-50/50 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <i class='bx bx-bell text-[22px] transition-colors duration-300 {{ $notificationCount > 0 ? 'text-blue-500 animate-swing' : 'text-slate-400 group-hover:text-blue-400' }}'></i>
        
        @if($notificationCount > 0)
            <span class="absolute -top-2 -right-2 flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-red-500 rounded-full text-[11px] leading-none font-bold text-white shadow-md border-2 border-white" wire:key="badge-{{ $notificationCount }}">
                {{ $notificationCount > 9 ? '9+' : $notificationCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown (Soft Elegant Light Mode) --}}
    <div x-show="open" 
         @click.away="open = false" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute right-0 bottom-full mb-4 w-80 sm:w-[420px] rounded-3xl overflow-hidden z-[1000] origin-bottom-right shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1),0_0_0_1px_rgba(0,0,0,0.02)] border border-white"
         style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        
        <div class="relative z-20 flex flex-col h-full">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100/80 bg-slate-50/30 flex flex-col gap-3 relative overflow-hidden">
                <!-- Subtle color strip at the top -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400"></div>

                <div class="flex items-center justify-between">
                    <h3 class="text-[15px] font-bold text-slate-800 flex items-center gap-2">
                        Notifications
                        @if($notificationCount > 0)
                            <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold shadow-sm">
                                {{ $notificationCount }} New
                            </span>
                        @endif
                    </h3>
                </div>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="flex items-center gap-2 mt-2 px-1">
                        <input type="checkbox" wire:model.live="readGlobal" id="readGlobalNotifs" class="rounded text-blue-600 border-gray-300 shadow-sm cursor-pointer w-3.5 h-3.5 focus:ring-blue-500">
                        <label for="readGlobalNotifs" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider cursor-pointer select-none">Include all employee notifications</label>
                    </div>
                @endif
                
                <div class="flex gap-2 w-full mt-2">
                    @if($notificationCount > 0)
                        <button wire:click.prevent="markAllAsRead" 
                                type="button"
                                class="flex-1 flex items-center justify-center gap-1.5 text-[10px] uppercase tracking-widest font-bold px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 rounded-xl transition-all active:scale-95 duration-200">
                            <i class='bx bx-check-double text-[13px] text-emerald-500'></i> Read All
                        </button>
                    @endif
                    @if($notifications->count() > 0)
                        <button wire:click.prevent="clearAll" 
                                type="button"
                                class="flex-1 flex items-center justify-center gap-1.5 text-[10px] uppercase tracking-widest font-bold px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 rounded-xl transition-all active:scale-95 duration-200 group">
                            <i class='bx bx-trash text-[13px] group-hover:scale-110 transition-transform'></i> Clear All
                        </button>
                    @endif
                </div>
            </div>

            {{-- Notifications List --}}
            <div class="max-h-[380px] overflow-y-auto custom-scroll-light bg-white/50">
                @forelse($notifications as $notification)
                    @php
                        $message = $notification->data['message'] ?? '';
                        $appId = $notification->data['applicant_id'] ?? '#';
                        $studentId = $notification->data['student_id'] ?? null;
                        $action = $notification->data['action'] ?? null;
                        
                        $isEnrollment = str_starts_with($message, 'Enrollment form submitted');
                        $isFinalized = $action === 'finalized' || $action === 'bulk_finalized';
                        $isUnfinalized = $action === 'unfinalized' || $action === 'bulk_unfinalized';
                        
                        $targetRoute = $isEnrollment 
                            ? route('official-enrollment.show', ['id' => $appId])
                            : route('admission.show', ['id' => $appId]);
                            
                        if ($studentId) {
                            $targetRoute = route('students.show', ['student' => $studentId]);
                        } elseif ($isFinalized || $isUnfinalized) {
                            $targetRoute = route('students.index'); // Bulk actions redirect to list
                        }
                            
                        $isRead = !is_null($notification->read_at);
                        
                        // Soft Light Iconography
                        if ($isFinalized) {
                            $iconClass = 'bx-check-shield';
                            $iconColor = 'text-emerald-600';
                            $iconBg = 'bg-emerald-50';
                            $iconBorder = 'border-emerald-100';
                        } elseif ($isUnfinalized) {
                            $iconClass = 'bx-lock-open';
                            $iconColor = 'text-amber-600';
                            $iconBg = 'bg-amber-50';
                            $iconBorder = 'border-amber-100';
                        } else {
                            $iconClass = $isEnrollment ? 'bx-file-blank' : 'bx-user-circle';
                            $iconColor = $isEnrollment ? 'text-purple-500' : 'text-blue-500';
                            $iconBg = $isEnrollment ? 'bg-purple-50' : 'bg-blue-50';
                            $iconBorder = $isEnrollment ? 'border-purple-100' : 'border-blue-100';
                        }
                        
                        $itemBg = 'hover:bg-slate-50/80 border-l-[3px] border-l-transparent';
                        
                        if (!$isRead) {
                            $itemBg = 'bg-blue-50/30 hover:bg-blue-50/60 border-l-[3px] border-l-blue-400';
                        } else {
                            $iconColor = 'text-slate-400';
                            $iconBg = 'bg-slate-50';
                            $iconBorder = 'border-slate-100';
                        }
                    @endphp
                    
                    <a href="{{ $targetRoute }}" 
                       wire:click.prevent="markAsRead('{{ $notification->id }}')"
                       wire:key="notif-{{ $notification->id }}"
                       class="group flex items-start gap-4 px-6 py-4 transition-colors duration-200 decoration-none relative {{ $itemBg }} border-b border-slate-50 last:border-0">
                        
                        <div class="flex-shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-105 border {{ $iconBorder }} {{ $iconBg }}">
                            <i class='bx {{ $iconClass }} text-xl {{ $iconColor }}'></i>
                        </div>

                        <div class="flex-1 min-w-0 py-0.5">
                            <p class="text-[13px] leading-[1.3] mb-1.5 transition-colors {{ $isRead ? 'font-medium text-slate-500 group-hover:text-slate-700' : 'font-semibold text-slate-800 group-hover:text-blue-700' }}">
                                {{ $message ?: 'System Notification' }}
                            </p>
                            <p class="text-[10px] font-semibold uppercase flex items-center gap-1 text-slate-400">
                                <i class='bx bx-time-five'></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        
                        <div class="flex-shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity translate-x-1 group-hover:translate-x-0 duration-200">
                            <i class='bx bx-chevron-right text-xl text-slate-300 group-hover:text-blue-400'></i>
                        </div>
                    </a>
                @empty
                    <div class="py-16 flex flex-col items-center justify-center text-slate-400">
                        <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 shadow-sm">
                            <i class='bx bx-check text-3xl text-slate-300'></i>
                        </div>
                        <p class="text-[14px] font-bold text-slate-600">All caught up!</p>
                        <p class="text-[12px] text-slate-400 mt-1">You have no new notifications.</p>
                    </div>
                @endforelse
            </div>
            
            @if($notifications->count() > 0)
            {{-- Footer --}}
            <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 text-center">
                <span class="text-[10px] text-slate-400 font-medium">End of notifications</span>
            </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes swing {
            0%, 100% { transform: rotate(0deg); }
            20% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(5deg); }
            80% { transform: rotate(-5deg); }
        }
        .animate-swing { animation: swing 1.5s ease-in-out infinite; transform-origin: top center; }
        [x-cloak] { display: none !important; }
        
        /* Custom Light Scrollbar */
        .custom-scroll-light::-webkit-scrollbar { width: 5px; }
        .custom-scroll-light::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll-light::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 10px; }
        .custom-scroll-light::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.5); }
    </style>
</div>
