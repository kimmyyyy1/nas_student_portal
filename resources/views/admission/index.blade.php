<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase shadow-sm border border-blue-200">
                <img src="{{ asset('images/nas/NASCENT SAS ICON.png') }}" alt="NASCENT SAS ICON" class="h-4 w-4 mr-1.5"> NASCENT SAS
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/nas/NASCENT SAS ICON.png') }}" alt="NASCENT SAS ICON" class="h-12 w-auto">
                <h2 class="text-2xl font-bold text-gray-800">NASCENT SAS</h2>
            </div>
            {{-- LIVE INDICATOR --}}
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-2 h-2 bg-red-600 rounded-full mr-1.5"></span> LIVE
            </span>
        </div>

    </x-slot>

    {{-- 👇 FIX: 'py-4' sa mobile para tamang-tama ang taas, 'md:py-12' sa desktop --}}
    <div class="py-4 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            @livewire('admission-masterlist')
        </div>
    </div>
</x-app-layout>