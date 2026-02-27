<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title flex border-none">
            {{ __('Bulk Upload Student Photos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="premium-card !p-0 overflow-hidden">
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
                                    <p class="mb-2">Ensure filenames match the <strong>STUDENT ID</strong> exactly.</p>
                                    <ul class="list-disc list-inside ml-2">
                                        <li>Correct: <strong>2026-0001.jpg</strong></li>
                                        <li>Max file size per image: <strong>4MB</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PROGRESS AREA --}}
                    <div id="progress-area" class="hidden mb-6">
                        <div class="mb-2 flex justify-between text-sm font-medium text-gray-700">
                            <span id="progress-text">Processing...</span>
                            <span id="progress-percent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <div id="log-area" class="mt-4 p-3 bg-gray-100 rounded h-40 overflow-y-auto text-xs font-mono border border-gray-300">
                            {{-- Logs will appear here --}}
                        </div>
                    </div>

                    {{-- FORM --}}
                    <form id="bulk-upload-form">
                        @csrf

                        <div class="mb-6 flex flex-col items-center justify-center border-2 border-gray-300 border-dashed rounded-lg p-10 bg-gray-50 hover:bg-gray-100 transition">
                            <i class='bx bxs-cloud-upload text-6xl text-gray-400 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900">Select Multiple Photos</h3>
                            <p class="text-sm text-gray-500 mb-6">Drag and drop or click to browse</p>
                            
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer text-center" required>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('students.index') }}" class="text-gray-600 hover:underline">Back to Directory</a>
                            <button type="submit" id="upload-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                START UPLOAD
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT FOR SEQUENTIAL UPLOAD --}}
    <script>
        document.getElementById('bulk-upload-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('photos');
            const files = fileInput.files;
            
            if (files.length === 0) {
                alert("Please select files first.");
                return;
            }

            // UI Setup
            const uploadBtn = document.getElementById('upload-btn');
            const progressArea = document.getElementById('progress-area');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const progressPercent = document.getElementById('progress-percent');
            const logArea = document.getElementById('log-area');

            uploadBtn.disabled = true;
            uploadBtn.classList.add('opacity-50', 'cursor-not-allowed');
            uploadBtn.innerText = "Uploading... Please Wait";
            progressArea.classList.remove('hidden');
            logArea.innerHTML = ''; // Clear logs

            let successCount = 0;
            let failCount = 0;

            // Process files one by one
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const formData = new FormData();
                formData.append('photos[]', file); // Send as array of 1
                formData.append('_token', '{{ csrf_token() }}');

                // Update Progress UI
                const percent = Math.round(((i) / files.length) * 100);
                progressBar.style.width = percent + "%";
                progressPercent.innerText = percent + "%";
                progressText.innerText = `Uploading ${i + 1} of ${files.length}: ${file.name}...`;

                // Log entry
                const logItem = document.createElement('div');
                logItem.innerText = `> Uploading ${file.name}...`;
                logArea.prepend(logItem);

                try {
                    const response = await fetch("{{ route('students.process-bulk-upload') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        successCount++;
                        logItem.innerHTML += " <span class='text-green-600 font-bold'>OK</span>";
                    } else {
                        failCount++;
                        logItem.innerHTML += ` <span class='text-red-600 font-bold'>FAILED: ${result.message || 'Unknown Error'}</span>`;
                    }

                } catch (error) {
                    console.error(error);
                    failCount++;
                    logItem.innerHTML += " <span class='text-red-600 font-bold'>NETWORK ERROR</span>";
                }
            }

            // Finish
            progressBar.style.width = "100%";
            progressPercent.innerText = "100%";
            progressText.innerText = "Done!";
            
            alert(`Process Complete!\nSuccess: ${successCount}\nFailed: ${failCount}\n\nCheck the log for details.`);
            
            // Reload page to update directory
            window.location.href = "{{ route('students.index') }}";
        });
    </script>
</x-app-layout>