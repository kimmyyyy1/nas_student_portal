<x-app-layout>
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Training Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    {{-- Error Handling (Optional but recommended) --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('training-plans.store') }}">
                        @csrf 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            {{-- Plan Name --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Plan Name</label>
                                <input type="text" name="plan_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('plan_name') }}" required>
                            </div>

                            {{-- Assign Team --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Assign Team</label>
                                <select name="team_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Select Team --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->team_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('start_date') }}" required>
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('end_date') }}" required>
                            </div>

                        </div>

                        {{-- Details --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Details</label>
                            <textarea name="details" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('details') }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('training-plans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                                Save Plan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>