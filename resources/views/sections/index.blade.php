<x-app-layout>
    {{-- Font Style Override --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center font-poppins-override">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sections & Classes') }}
            </h2>
            {{-- 👇 DITO NATIN ITETELEPORT ANG BUTTON GALING SA LIVEWIRE --}}
            <div id="header-actions"></div>
        </div>
    </x-slot>

    <div class="py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Livewire Component --}}
            @livewire('sections-manager')
        </div>
    </div>
</x-app-layout>