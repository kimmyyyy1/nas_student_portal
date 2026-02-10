<div wire:poll.5s> {{-- ⚡ POLLING --}}
    
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end" x-data="{ open: false }">

        {{-- Dropdown Panel --}}
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-10 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-10 scale-95"
             class="mb-4 w-80 md:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden ring-1 ring-black/5"
             style="display: none;">
            
            <div class="p-4 bg-gradient-to-r from-slate-900 to-slate-800 text-white flex justify-between items-center relative z-20">
                <span class="font-bold text-sm uppercase tracking-wide">Notifications</span>
                
                {{-- 
                    ✅ ULTIMATE FIX: STANDARD LINK 
                    Gagamitin natin ang route('notifications.readAll') mula sa web.php.
                    Ito ay magre-reload ng page, kaya 100% siguradong mawawala ang badge.
                --}}
                @if($unreadCount > 0)
                    <a href="{{ route('notifications.readAll') }}" 
                       class="text-[10px] font-bold bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg transition-colors border border-white/10 shadow-sm hover:no-underline text-center cursor-pointer">
                        Mark all read
                    </a>
                @endif
            </div>

            <div class="max-h-80 overflow-y-auto custom-scroll bg-white relative z-10">
                @forelse($notifications as $notification)
                    {{-- Item Link --}}
                    <a href="{{ $notification->data['link'] ?? '#' }}?read={{ $notification->id }}" 
                       class="block w-full text-left px-4 py-3 border-b border-gray-100 transition-all relative group
                              {{ is_null($notification->read_at) ? 'bg-blue-50/50 hover:bg-blue-100' : 'bg-white hover:bg-gray-50' }}">
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                {{-- Icons --}}
                                @if(isset($notification->data['type']) && $notification->data['type'] == 'resubmission')
                                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shadow-sm">
                                        <i class='bx bx-revision text-lg'></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm">
                                        <i class='bx bx-user-plus text-lg'></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold {{ is_null($notification->read_at) ? 'text-gray-900' : 'text-gray-600' }} truncate">
                                    {{ $notification->data['name'] ?? 'System' }}
                                </p>
                                <p class="text-[11px] {{ is_null($notification->read_at) ? 'text-gray-800 font-medium' : 'text-gray-500' }} leading-snug line-clamp-2">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                    <i class='bx bx-time-five'></i>
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Blue Dot --}}
                            @if(is_null($notification->read_at))
                                <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 shadow-sm animate-pulse flex-shrink-0"></div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center text-gray-400">
                        <i class='bx bx-bell-off text-4xl mb-2 opacity-50'></i>
                        <p class="text-xs font-medium">No notifications yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Bell Button --}}
        <button @click="open = !open" class="relative group outline-none z-50">
            <div class="w-14 h-14 bg-slate-900 hover:bg-blue-600 text-white rounded-full shadow-lg shadow-slate-400/50 flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                <i class='bx bxs-bell text-2xl group-hover:animate-swing'></i>
            </div>

            {{-- Red Badge --}}
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 w-6 h-6 bg-red-600 border-2 border-white rounded-full text-white text-[10px] font-bold flex items-center justify-center shadow-sm animate-bounce-slight">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>
    </div>

    {{-- Toast Alert (Popup) --}}
    @if($showAlert && $latest)
        <div class="fixed top-20 right-4 z-[100] flex flex-col gap-2 pointer-events-none"
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 8000)" 
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transform transition ease-in duration-300"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0">
             
             <a href="{{ $latest->data['link'] ?? '#' }}?read={{ $latest->id }}" 
                class="pointer-events-auto w-80 bg-white border-l-4 border-blue-600 shadow-xl rounded-lg p-4 flex items-start gap-3 ring-1 ring-black/5 hover:bg-blue-50 transition text-left cursor-pointer decoration-0">
                <div class="flex-shrink-0 text-blue-600">
                     <i class='bx bxs-bell-ring text-2xl animate-pulse'></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-800">New Alert!</h3>
                    <p class="text-xs text-gray-600 mt-0.5 font-medium leading-snug">
                        <span class="font-bold text-gray-900">{{ $latest->data['name'] ?? '' }}</span> 
                        {{ $latest->data['message'] ?? '' }}
                    </p>
                    <p class="text-[10px] text-gray-400 mt-1">Just now</p>
                </div>
            </a>
        </div>
    @endif

</div>