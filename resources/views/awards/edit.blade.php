<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title flex border-none">
            {{ __('Edit Award Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="premium-card !p-0 overflow-hidden">
                
                <form method="POST" action="{{ route('awards.update', $award->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Student Name *</label>
                        <select name="student_id" class="w-full rounded-md border-gray-300 shadow-sm" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $award->student_id == $student->id ? 'selected' : '' }}>
                                    {{ $student->last_name }}, {{ $student->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Award Title *</label>
                        <input type="text" name="award_name" class="w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('award_name', $award->award_name) }}">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category *</label>
                            <select name="category" class="w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="Academic" {{ $award->category == 'Academic' ? 'selected' : '' }}>Academic</option>
                                <option value="Sports" {{ $award->category == 'Sports' ? 'selected' : '' }}>Sports</option>
                                <option value="Special" {{ $award->category == 'Special' ? 'selected' : '' }}>Special</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Date Received *</label>
                            <input type="date" name="date_received" class="w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('date_received', $award->date_received) }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description / Remarks</label>
                        <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $award->description) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('awards.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-bold text-sm">Cancel</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-bold text-sm shadow">Update Record</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>