<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Team') }}
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

                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="team_name" class="block text-sm font-medium text-gray-700">Team Name</label>
                                <input type="text" name="team_name" id="team_name" placeholder="e.g., NAS Swim Team" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('team_name') }}" required>
                            </div>

                            <div>
                                <label for="sport" class="block text-sm font-medium text-gray-700">Sport</label>
                                <input type="text" name="sport" id="sport" placeholder="e.g., Swimming, Athletics" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('sport') }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="coach_name" class="block text-sm font-medium text-gray-700">Coach Name (Optional)</label>
                                <input type="text" name="coach_name" id="coach_name" placeholder="e.g., Coach Gaindov" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('coach_name') }}">
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Save Team
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>