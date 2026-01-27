<x-app-layout>
    <x-slot name="header">
        <div class="flex md:hidden items-center justify-between w-full py-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 uppercase shadow-sm border border-purple-200">
                <i class='bx bxs-directions mr-1.5 text-sm'></i> Training
            </span>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>
        </div>

        <div class="hidden md:flex items-center justify-between w-full py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Create Training Plan') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="py-2 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm text-sm">
                            <div class="flex items-center mb-2">
                                <i class='bx bx-error-circle mr-2 text-xl'></i>
                                <span class="font-bold">Whoops! Something went wrong.</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
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
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Plan Name</label>
                                <input type="text" name="plan_name" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                       value="{{ old('plan_name') }}" required placeholder="e.g. Pre-season Conditioning">
                            </div>

                            {{-- Assign Focus Sport (DROPDOWN FIXED) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Assign Focus Sport</label>
                                <select name="team_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer">
                                    <option value="">-- Select Focus Sport --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                            {{-- 👇 FIXED: DISPLAY ONLY SPORT NAME --}}
                                            {{ $team->sport }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                                <input type="date" name="start_date" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer" 
                                       value="{{ old('start_date') }}" required>
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Date</label>
                                <input type="date" name="end_date" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer" 
                                       value="{{ old('end_date') }}" required>
                            </div>

                        </div>

                        {{-- Details --}}
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Details</label>
                            <textarea name="details" rows="4" 
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                      placeholder="Describe the training plan objectives and routine...">{{ old('details') }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('training-plans.index') }}" wire:navigate class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded text-sm shadow-md transition transform hover:-translate-y-0.5">
                                Save Plan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>