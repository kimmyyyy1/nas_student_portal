<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 uppercase shadow-sm border border-yellow-200">
                <i class='bx bxs-edit mr-1.5 text-sm'></i> Edit Team
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Edit Team') }}: <span class="text-indigo-600 ml-2">{{ $team->team_name }}</span>
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
        </div>

    </x-slot>

    {{-- 👇 FIX: 'py-2' mobile, 'md:py-12' desktop --}}
    <div class="py-2 md:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm text-sm">
                            <div class="flex items-center mb-2">
                                <i class='bx bx-error-circle mr-2 text-xl'></i>
                                <span class="font-bold">Please correct the errors below:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teams.update', $team->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            {{-- Team Name --}}
                            <div>
                                <label for="team_name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Team Name</label>
                                <input type="text" name="team_name" id="team_name" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                       value="{{ old('team_name', $team->team_name) }}" required>
                            </div>

                            {{-- Sport --}}
                            <div>
                                <label for="sport" class="block text-xs font-bold text-gray-500 uppercase mb-1">Sport</label>
                                <select name="sport" id="sport" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer" required>
                                    <option value="">-- Select Sport --</option>
                                    @foreach(['Taekwondo', 'Table Tennis', 'Judo', 'Gymnastics', 'Badminton', 'Athletics', 'Aquatics', 'Arnis', 'Archery', 'Wrestling', 'Weightlifting'] as $s)
                                        <option value="{{ $s }}" {{ (old('sport', $team->sport) == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                    <option value="Other" {{ (old('sport', $team->sport) == 'Other') ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            {{-- Coach Name --}}
                            <div class="md:col-span-2">
                                <label for="coach_name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Coach Name (Optional)</label>
                                <input type="text" name="coach_name" id="coach_name" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                       value="{{ old('coach_name', $team->coach_name) }}">
                            </div>

                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('teams.index') }}" wire:navigate class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded text-sm shadow-md transition transform hover:-translate-y-0.5">
                                Update Team
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>