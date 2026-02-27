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
        <div class="p-6 text-gray-900 border-b border-white/40">

            {{-- DYNAMIC TITLE --}}
            @if($isCreating)
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700">Add New Subject</h3>
                </div>
            @elseif($isEditing)
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700">Edit Subject: {{ $subject_name }}</h3>
                </div>
            @endif

            {{-- VIEW 1: TABLE LIST --}}
            @if(!$isCreating && !$isEditing)
                <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                    <table class="min-w-full divide-y divide-gray-100/50 whitespace-nowrap bg-transparent">
                        <thead class="premium-table-header">
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Description</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @forelse ($subjects as $subject)
                                <tr class="premium-table-row group">
                                    <td class="premium-table-cell text-[13px] font-black tracking-widest text-indigo-600 uppercase">
                                        {{ $subject->subject_code }}
                                    </td>
                                    <td class="premium-table-cell font-bold text-slate-800">
                                        {{ $subject->subject_name }}
                                    </td>
                                    <td class="premium-table-cell text-xs text-slate-500 font-medium">
                                        {{ Str::limit($subject->description, 50) ?? 'N/A' }}
                                    </td>
                                    <td class="premium-table-cell text-right text-[13px] font-bold">
                                        {{-- Edit Button --}}
                                        <button wire:click="edit({{ $subject->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 cursor-pointer">Edit</button>
                                        
                                        {{-- Delete Button --}}
                                        <button wire:click="delete({{ $subject->id }})" 
                                                wire:confirm="Are you sure you want to delete this subject?"
                                                class="text-red-600 hover:text-red-900 font-bold cursor-pointer">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 whitespace-nowrap text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class='bx bx-book text-4xl text-gray-300 mb-2'></i>
                                            <p class="text-lg font-medium">No subjects found.</p>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Subject Code --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Subject Code *</label>
                            <input type="text" wire:model="subject_code" placeholder="e.g. MATH101" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('subject_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Subject Name --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Subject Name *</label>
                            <input type="text" wire:model="subject_name" placeholder="e.g. Calculus 1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('subject_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Description (Optional)</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="cancel" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out cursor-pointer">
                            {{ $isCreating ? 'Save Subject' : 'Update Subject' }}
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>