<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
    </style>

    {{-- HEADER --}}
    <x-slot name="header">
        {{-- 👇 FIX: Layout Container --}}
        <div class="flex flex-row justify-between items-center gap-3 font-poppins-override">
            
            {{-- TITLE (Will shrink if text is too long) --}}
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight truncate min-w-0">
                {{ __('Sections & Classes') }}
            </h2>

            {{-- 👇 FIX: BUTTON (Always Visible & Compact) --}}
            {{-- Tinanggal ang x-show/x-cloak para sigurado lumabas --}}
            <button onclick="document.getElementById('hidden-create-btn').click()"
                    class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 md:px-4 rounded shadow-sm text-xs md:text-sm transition duration-150 ease-in-out cursor-pointer flex items-center whitespace-nowrap">
                <i class='bx bx-plus mr-1 md:mr-2 text-base md:text-lg'></i> 
                Add Section
            </button>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 font-poppins-override">
        {{-- Padding fix for mobile --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            {{-- Livewire Component --}}
            @livewire('sections-manager')
        </div>
    </div>
</x-app-layout>