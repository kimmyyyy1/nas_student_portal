<x-app-layout>
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <h2 class="page-title border-none flex items-center">
            Edit Subject: <span class="text-indigo-600 ml-2">{{ $subject->subject_name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('subjects.update', $subject->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="subject_code" class="block text-sm font-bold text-gray-700 mb-1">Subject Code</label>
                                <input type="text" name="subject_code" id="subject_code" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       value="{{ old('subject_code', $subject->subject_code) }}" required>
                            </div>

                            <div>
                                <label for="subject_name" class="block text-sm font-bold text-gray-700 mb-1">Subject Name</label>
                                <input type="text" name="subject_name" id="subject_name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       value="{{ old('subject_name', $subject->subject_name) }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Description (Optional)</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $subject->description) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('subjects.index') }}" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded text-sm shadow-md transition transform hover:-translate-y-0.5">
                                Update Subject
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>