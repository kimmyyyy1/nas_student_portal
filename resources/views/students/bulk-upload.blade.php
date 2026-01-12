<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bulk Upload Student Photos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">

                    {{-- INSTRUCTIONS --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-blue-800 uppercase">IMPORTANT INSTRUCTION</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p class="mb-2">To automatically match photos to students, you must <strong>RENAME</strong> the image files using the student's <strong>LRN</strong>.</p>
                                    <ul class="list-disc list-inside ml-2">
                                        <li>Correct: <strong>123456789012.jpg</strong></li>
                                        <li>Correct: <strong>100020003000.png</strong></li>
                                        <li><span class="text-red-600">Wrong:</span> juan_cruz.jpg</li>
                                        <li><span class="text-red-600">Wrong:</span> photo(1).jpg</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ERROR DISPLAY --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                            <p class="font-bold">Errors encountered:</p>
                            <ul class="list-disc ml-8 text-sm">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.process-bulk-upload') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6 flex flex-col items-center justify-center border-2 border-gray-300 border-dashed rounded-lg p-10 bg-gray-50 hover:bg-gray-100 transition">
                            <i class='bx bxs-cloud-upload text-6xl text-gray-400 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900">Select Multiple Photos</h3>
                            <p class="text-sm text-gray-500 mb-6">Drag and drop or click to browse</p>
                            
                            {{-- IMPORTANT: 'multiple' attribute allows selecting many files --}}
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer text-center" required>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('students.index') }}" class="text-gray-600 hover:underline">Back to Directory</a>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded shadow-lg transform transition hover:-translate-y-0.5 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                START UPLOAD
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 text-center italic">Note: Large batches (50+) may take time. Please wait until the process completes.</p>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>