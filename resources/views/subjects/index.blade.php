<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex justify-between items-center font-poppins-override"
             x-data="{ showButton: true }"
             @toggle-add-button.window="showButton = $event.detail.show">
            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subjects') }}
            </h2>

            {{-- 👇 MANUAL ADD BUTTON --}}
            <button x-show="showButton"
                    x-cloak
                    onclick="document.getElementById('hidden-create-btn').click()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out cursor-pointer">
                Add Subject
            </button>
        </div>
    </x-slot>

    <div class="py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Livewire Component --}}
            @livewire('subjects-manager')
        </div>
    </div>
</x-app-layout>