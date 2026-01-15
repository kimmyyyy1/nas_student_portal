<x-app-layout>
    {{-- 👇 DIRECT INJECTION: Fonts & Styles (Global fallback) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        {{-- Empty header slot, nasa Livewire component na ang header text --}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- 👇 TINAWAG NATIN ANG LIVEWIRE COMPONENT DITO --}}
            @livewire('sections-manager')
        </div>
    </div>
</x-app-layout>