<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Advisory Class') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(isset($section))
                    <h3 class="text-lg font-bold">{{ $section->section_name }}</h3>
                    <p>Grade Level: {{ $section->grade_level }}</p>
                    
                    @else
                    <p class="text-red-500">You do not have an advisory class assigned.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>