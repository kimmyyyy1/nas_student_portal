<x-app-layout>
    {{-- Global Styles --}}
    <style>
        /* FIX: Apply Poppins sa lahat EXCEPT sa mga icons (.bx). */
        .font-poppins-override *:not(.bx) { 
            font-family: 'Poppins', sans-serif !important; 
        }
        
        [x-cloak] { display: none !important; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex justify-between items-center font-poppins-override"
             x-data="{ 
                 title: 'Select Class to Grade', 
                 showBack: false 
             }"
             @update-header.window="title = $event.detail.title; showBack = $event.detail.showBack">
            
            {{-- DYNAMIC TITLE --}}
            {{-- Added truncate/min-w-0 para hindi itulak ang layout pag mahaba ang text --}}
            <h2 class="page-title mr-2" x-text="title">
                {{ __('Select Class to Grade') }}
            </h2>

            {{-- 🟢 DESKTOP BACK BUTTON (Hidden on Mobile) --}}
            <button x-show="showBack"
                    x-cloak
                    x-transition
                    onclick="document.getElementById('hidden-back-btn').click()"
                    class="hidden md:flex premium-btn-secondary shrink-0">
                <i class='bx bx-arrow-back'></i> Back to Classes
            </button>
        </div>
    </x-slot>

    <div class="py-4 md:py-8 font-poppins-override">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE BACK BUTTON (Visible only on Mobile) --}}
            {{-- Kailangan ng sariling Alpine x-data para makinig din sa event --}}
            <div x-data="{ showBack: false }"
                 @update-header.window="showBack = $event.detail.showBack"
                 x-show="showBack"
                 x-cloak
                 x-transition
                 class="block md:hidden mb-6">
                
                <button onclick="document.getElementById('hidden-back-btn').click()"
                        class="premium-btn-secondary w-full justify-center">
                    <i class='bx bx-arrow-back mr-2'></i> Back to Classes
                </button>
            </div>

            {{-- Livewire Component --}}
            @livewire('grades-manager')
        </div>
    </div>
</x-app-layout>