<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Section') }}
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

                    <form method="POST" action="{{ route('sections.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Grade Level *</label>
                            <select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Select Grade --</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Section Name *</label>
                            <input type="text" name="section_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required placeholder="e.g. Emerald">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Class Adviser</label>
                            <select name="adviser_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->first_name }} {{ $teacher->last_name }}">
                                        {{ $teacher->last_name }}, {{ $teacher->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Select from registered teachers.</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Room Number</label>
                            <input type="text" name="room_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g. Rm 101">
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('sections.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md mr-2">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">Save Section</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>