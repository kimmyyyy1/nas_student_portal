<div>
    {{-- 👇 TELEPORT MAGIC: Ilalagay nito ang button sa Header sa taas --}}
    @teleport('#header-actions')
        {{-- Ipakita lang ang button kapag HINDI nag-aadd at HINDI nag-eedit --}}
        @if(!$isCreating && !$isEditing)
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out">
                Add Section
            </button>
        @endif
    @endteleport

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
                    <h3 class="text-lg font-bold text-gray-700">Create New Section</h3>
                </div>
            @elseif($isEditing)
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700">Edit Section</h3>
                </div>
            @endif

            {{-- VIEW 1: TABLE LIST --}}
            @if(!$isCreating && !$isEditing)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Section Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grade Level</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Adviser</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($sections as $section)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $section->section_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $section->grade_level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($section->adviser)
                                            <div class="text-sm font-medium text-gray-900">{{ $section->adviser->last_name }}, {{ $section->adviser->first_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $section->adviser->email }}</div>
                                        @else
                                            <span class="text-sm text-gray-400 italic">No Adviser Assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $section->room_number ?? 'TBA' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $section->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3">Edit</button>
                                        <button wire:click="delete({{ $section->id }})" 
                                                wire:confirm="Are you sure you want to delete this section?"
                                                class="text-red-600 hover:text-red-900 font-bold">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 whitespace-nowrap text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class='bx bx-chalkboard text-4xl text-gray-300 mb-2'></i>
                                            <p class="text-lg font-medium">No sections found.</p>
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
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Grade Level *</label>
                            <select wire:model="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Grade --</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                            @error('grade_level') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Section Name *</label>
                            <input type="text" wire:model="section_name" placeholder="e.g. Emerald" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('section_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Class Adviser</label>
                            <select wire:model="adviser_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->last_name }}, {{ $teacher->first_name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Select from registered teachers (Staff).</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Room Number</label>
                            <input type="text" wire:model="room_number" placeholder="e.g. Rm 101" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="cancel" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                            {{ $isCreating ? 'Save Section' : 'Update Section' }}
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>