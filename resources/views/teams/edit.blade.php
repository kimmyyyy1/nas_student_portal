<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Header na may pangalan ng team --}}
            Edit Team: {{ $team->team_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form ay papunta na sa 'teams.update' at may @method('PATCH') --}}
                    <form method="POST" action="{{ route('teams.update', $team->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="team_name" class="block text-sm font-medium text-gray-700">Team Name</label>
                                <input type="text" name="team_name" id="team_name" placeholder="e.g., NAS Swim Team" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('team_name', $team->team_name) }}" required>
                            </div>

                            <div>
                                <label for="sport" class="block text-sm font-medium text-gray-700">Sport</label>
                                <input type="text" name="sport" id="sport" placeholder="e.g., Swimming, Athletics" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('sport', $team->sport) }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="coach_name" class="block text-sm font-medium text-gray-700">Coach Name (Optional)</label>
                                <input type="text" name="coach_name" id="coach_name" placeholder="e.g., Coach Gaindov" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('coach_name', $team->coach_name) }}">
                            </div>

                        </div>

                        {{-- BUTTONS GROUP --}}
                        <div class="flex justify-end items-center gap-4 mt-6 pt-4 border-t border-gray-100">
                            
                            {{-- 👇 CANCEL BUTTON --}}
                            <a href="{{ route('teams.index') }}" 
                               class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition shadow-sm border border-gray-300">
                                Cancel
                            </a>

                            {{-- UPDATE BUTTON (Existing) --}}
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                                Update Team
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>