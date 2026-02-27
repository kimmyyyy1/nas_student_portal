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

    <div class="premium-card !p-0 overflow-hidden">
        <div class="p-6 md:p-8 text-gray-900 border-b border-white/40">

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
                <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                    <table class="min-w-full divide-y divide-gray-100/50 whitespace-nowrap bg-transparent">
                        <thead class="premium-table-header">
                            <tr>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Teacher</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @forelse ($schedules as $schedule)
                                <tr class="premium-table-row group">
                                    <td class="premium-table-cell text-[13px] font-black tracking-widest text-indigo-600 uppercase">
                                        {{ $schedule->subject->subject_name ?? 'N/A' }}
                                    </td>
                                    <td class="premium-table-cell font-bold text-slate-800">
                                        <div class="px-2 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-widest rounded-md bg-blue-100 text-blue-800 shadow-sm border border-blue-200/50">
                                            {{ $schedule->section->section_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="premium-table-cell">
                                        <div class="font-bold text-[13px] text-slate-800">{{ optional($schedule->staff)->first_name }} {{ optional($schedule->staff)->last_name }}</div>
                                    </td>
                                    <td class="premium-table-cell">
                                        <span class="font-bold text-teal-600 text-[13px] uppercase tracking-widest">{{ $schedule->day }}</span><br>
                                        <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">
                                            {{ date('g:i A', strtotime($schedule->time_start)) }} - {{ date('g:i A', strtotime($schedule->time_end)) }}
                                        </span>
                                    </td>
                                    <td class="premium-table-cell font-bold text-[13px] text-slate-500 uppercase tracking-widest">{{ $schedule->room ?? 'N/A' }}</td>
                                    <td class="premium-table-cell text-right text-[13px] font-bold">
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
                                            {{-- 👇 FIXED: Replaced 'bx-calendar' with SVG Icon --}}
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
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