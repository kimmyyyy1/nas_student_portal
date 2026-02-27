<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Enrollment Management') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-8">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            {{-- CALL LIVEWIRE COMPONENT --}}
            <livewire:enrollment-list />

        </div>
    </div>
</x-app-layout>