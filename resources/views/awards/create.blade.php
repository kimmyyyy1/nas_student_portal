<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record New Award') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
                        <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('awards.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Student Name *</label>
                        <select name="student_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Select Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->last_name }}, {{ $student->first_name }} ({{ $student->grade_level }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Award Title *</label>
                        <input type="text" name="award_name" class="w-full rounded-md border-gray-300 shadow-sm" required placeholder="e.g. With High Honors, MVP" value="{{ old('award_name') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category *</label>
                            <select name="category" class="w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="Academic">Academic</option>
                                <option value="Sports">Sports</option>
                                <option value="Special">Special</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Date Received *</label>
                            <input type="date" name="date_received" class="w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('date_received') }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description / Remarks</label>
                        <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Optional details...">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('awards.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-bold text-sm">Cancel</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-bold text-sm shadow">Save Record</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>