<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" x-text="title">
                {{ __('Select Class to Grade') }}
            </h2>

            {{-- DYNAMIC BACK BUTTON --}}
            <button x-show="showBack"
                    x-cloak
                    x-transition
                    onclick="document.getElementById('hidden-back-btn').click()"
                    class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out cursor-pointer">
                <i class='bx bx-arrow-back'></i> Back to Classes
            </button>
        </div>
    </x-slot>

    <div class="py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Livewire Component --}}
            @livewire('grades-manager')
        </div>
    </div>
</x-app-layout>