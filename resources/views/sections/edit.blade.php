<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Section') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('sections.update', $section->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Grade Level *</label>
                            <select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="Grade 7" {{ $section->grade_level == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                <option value="Grade 8" {{ $section->grade_level == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                <option value="Grade 9" {{ $section->grade_level == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                <option value="Grade 10" {{ $section->grade_level == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                <option value="Grade 11" {{ $section->grade_level == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ $section->grade_level == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Section Name *</label>
                            <input type="text" name="section_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('section_name', $section->section_name) }}">
                        </div>

                        {{-- FIX: Adviser Selection gamit ang ID --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Class Adviser</label>
                            {{-- Name Attribute: adviser_id --}}
                            <select name="adviser_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    {{-- Value: Staff ID --}}
                                    {{-- Condition: Check kung match ang ID --}}
                                    <option value="{{ $teacher->id }}" 
                                        {{ (old('adviser_id', $section->adviser_id) == $teacher->id) ? 'selected' : '' }}>
                                        
                                        {{ $teacher->last_name }}, {{ $teacher->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Room Number</label>
                            <input type="text" name="room_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('room_number', $section->room_number) }}">
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('sections.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-2">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">Update Section</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>