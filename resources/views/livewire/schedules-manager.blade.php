<div>
    {{-- 👇 HIDDEN BUTTON: Ito ang pipindutin ng button sa taas --}}
    <button id="hidden-create-btn" wire:click="create" style="display: none;"></button>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="p-6 text-gray-900">

            {{-- DYNAMIC TITLE --}}
            @if($isCreating)
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700">Add New Schedule</h3>
                </div>
            @elseif($isEditing)
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700">Edit Schedule</h3>
                </div>
            @endif

            {{-- VIEW 1: TABLE LIST --}}
            @if(!$isCreating && !$isEditing)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Section</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($schedules as $schedule)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $schedule->subject->subject_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $schedule->section->section_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ optional($schedule->staff)->first_name }} {{ optional($schedule->staff)->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-medium">{{ $schedule->day }}</span><br>
                                        <span class="text-xs text-gray-500">
                                            {{ date('g:i A', strtotime($schedule->time_start)) }} - {{ date('g:i A', strtotime($schedule->time_end)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $schedule->room ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Edit Button --}}
                                        <button wire:click="edit({{ $schedule->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 cursor-pointer">Edit</button>
                                        
                                        {{-- Delete Button --}}
                                        <button wire:click="delete({{ $schedule->id }})" 
                                                wire:confirm="Are you sure you want to delete this schedule?"
                                                class="text-red-600 hover:text-red-900 font-bold cursor-pointer">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 whitespace-nowrap text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class='bx bx-calendar text-4xl text-gray-300 mb-2'></i>
                                            <p class="text-lg font-medium">No schedules found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            {{-- VIEW 2: FORM (Create or Edit) --}}
            @else
                <form wire:submit.prevent="{{ $isCreating ? 'store' : 'update' }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        {{-- Subject --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Subject *</label>
                            <select wire:model="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Section --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Section *</label>
                            <select wire:model="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Section --</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->grade_level }} - {{ $section->section_name }}</option>
                                @endforeach
                            </select>
                            @error('section_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Teacher --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Teacher *</label>
                            <select wire:model="staff_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Staff --</option>
                                @foreach($staff as $person)
                                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                                @endforeach
                            </select>
                            @error('staff_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Day --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Day(s) *</label>
                            <input type="text" wire:model="day" placeholder="e.g. MWF" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('day') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Time Start --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Time Start *</label>
                            <input type="time" wire:model="time_start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('time_start') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Time End --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Time End *</label>
                            <input type="time" wire:model="time_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('time_end') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Room --}}
                        <div class="md:col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Room (Optional)</label>
                            <input type="text" wire:model="room" placeholder="e.g. Room 101" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="cancel" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out cursor-pointer">
                            {{ $isCreating ? 'Save Schedule' : 'Update Schedule' }}
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>