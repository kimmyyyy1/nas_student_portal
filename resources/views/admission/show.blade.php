<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <a href="{{ route('admission.index') }}" class="text-gray-400 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <span class="border-l-2 border-gray-300 pl-3 ml-1">{{ __('Review Admission Application') }}</span>
            </h2>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg inline-flex items-center shadow-sm transition text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
                <a href="{{ route('admission.pdf', $application->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center shadow-md transition text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            @page { margin: 0.5cm; size: auto; }
            html, body { height: 100%; margin: 0 !important; padding: 0 !important; overflow: visible !important; background: white !important; }
            nav, header, footer, .no-print, .shadow-xl, .border-t-4, x-app-layout, .min-h-screen { display: none !important; }
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible !important; }
            #print-area { position: absolute !important; left: 0 !important; top: 0 !important; width: 100% !important; margin: 0 !important; padding: 0 !important; background: white !important; border: none !important; box-shadow: none !important; }
            .md\:col-span-3 { width: 100% !important; display: block !important; }
            .grid { display: block !important; }
            .md\:flex-row { display: flex !important; flex-direction: row !important; } 
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .admin-input { display: none !important; }
        }
    </style>

    {{-- CALL THE LIVEWIRE COMPONENT --}}
    {{-- We pass the application ID so the component can fetch fresh data --}}
    <livewire:admin-application-review :id="$application->id" />

</x-app-layout>