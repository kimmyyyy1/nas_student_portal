<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase shadow-sm border border-green-200">
                <i class='bx bxs-user-detail mr-1.5 text-sm'></i> Directory
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-2">
            
            {{-- TITLE --}}
            <div class="flex items-center justify-between w-full md:w-auto">
                <h2 class="font-black text-2xl md:text-3xl text-slate-800 tracking-tight flex items-center">
                    {{ __('Student Directory') }}
                    <span class="ml-4 px-2.5 py-1 rounded-md text-[10px] font-black tracking-widest bg-rose-100 text-rose-600 animate-pulse flex items-center shadow-sm border border-rose-200">
                        <span class="w-1.5 h-1.5 bg-rose-600 rounded-full mr-1.5"></span> LIVE
                    </span>
                </h2>
            </div>
            
            {{-- HEADER BUTTONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('students.bulk-upload') }}" wire:navigate class="premium-btn-secondary w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Bulk Upload
                </a>
                {{-- Tinanggal na ang "New Student" button dito --}}
            </div>
        </div>
    </x-slot>

    <div class="py-2 md:py-8">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- ALERTS --}}
            {{-- Now handled by SweetAlert below --}}

            {{-- FILTER BAR & TABLE NOW HANDLED BY LIVEWIRE COMPONENT --}}
            <livewire:student-directory-table />

        </div>
    </div>
    
    <script>
        // Custom events from Livewire Component can be listened to here if needed.
    
    {{-- SweetAlert2 for Notifications --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: '{!! session('success') !!}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Action Failed',
                    html: '{!! session('error') !!}',
                    showConfirmButton: true,
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
</x-app-layout>