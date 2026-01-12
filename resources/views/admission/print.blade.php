<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - {{ $application->lrn }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 0.5cm; size: letter portrait; }
        }
        body { background: #e5e7eb; font-family: sans-serif; }
        .paper {
            width: 216mm;
            min-height: 279mm;
            background: white;
            margin: 20px auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
    </style>
</head>
<body>

    {{-- PRINT BUTTON (Hidden kapag nagpi-print na) --}}
    <div class="no-print fixed top-4 right-4 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            PRINT / SAVE AS PDF
        </button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
            Close
        </button>
    </div>

    {{-- ACTUAL DOCUMENT --}}
    <div class="paper">
        
        {{-- HEADER --}}
        <div class="text-center border-b-2 border-black pb-4 mb-6">
            <div class="flex justify-center items-center gap-4 mb-2">
                {{-- Logo: Pinalitan ko ng CDN/Text muna para sigurado --}}
                <img src="https://ui-avatars.com/api/?name=NAS&background=0D8ABC&color=fff&size=128" class="h-16 w-16 rounded-full">
            </div>
            <h1 class="text-2xl font-bold uppercase tracking-wide">National Academy of Sports</h1>
            <p class="text-sm text-gray-600">New Clark City, Capas, Tarlac</p>
            <h2 class="text-xl font-bold mt-4 uppercase border bg-gray-100 py-1">Student-Athlete Application Form</h2>
        </div>

        {{-- ID PHOTO & BASIC INFO --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <p><strong>Reference ID:</strong> <span class="font-mono text-red-600 font-bold">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                <p><strong>Date Filed:</strong> {{ $application->created_at->format('F d, Y') }}</p>
                <p><strong>Status:</strong> <span class="uppercase font-bold border px-2 text-xs">{{ $application->status }}</span></p>
            </div>
            <div class="border border-gray-400 w-32 h-32 flex items-center justify-center bg-gray-50">
                @if(isset($application->uploaded_files['id_picture']))
                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xs text-gray-400">2x2 Photo</span>
                @endif
            </div>
        </div>

        {{-- PERSONAL INFORMATION --}}
        <div class="mb-6">
            <h3 class="bg-gray-800 text-white px-2 py-1 font-bold uppercase text-sm mb-2">I. Personal Information</h3>
            <table class="w-full text-sm border-collapse border border-gray-300">
                <tr>
                    <td class="border p-2 bg-gray-50 w-1/4 font-bold">Full Name</td>
                    <td class="border p-2 uppercase font-bold">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</td>
                </tr>
                <tr>
                    <td class="border p-2 bg-gray-50 font-bold">LRN</td>
                    <td class="border p-2 font-mono">{{ $application->lrn }}</td>
                </tr>
                <tr>
                    <td class="border p-2 bg-gray-50 font-bold">Birthdate / Age</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }} ({{ $application->age }} years old)</td>
                </tr>
                <tr>
                    <td class="border p-2 bg-gray-50 font-bold">Address</td>
                    <td class="border p-2">{{ $application->barangay }}, {{ $application->municipality_city }}, {{ $application->province }}</td>
                </tr>
            </table>
        </div>

        {{-- ACADEMIC & SPORTS --}}
        <div class="mb-6">
            <h3 class="bg-gray-800 text-white px-2 py-1 font-bold uppercase text-sm mb-2">II. Academic & Sports Background</h3>
            <table class="w-full text-sm border-collapse border border-gray-300">
                <tr>
                    <td class="border p-2 bg-gray-50 w-1/4 font-bold">Applying For</td>
                    <td class="border p-2">{{ $application->grade_level_applied }}</td>
                </tr>
                <tr>
                    <td class="border p-2 bg-gray-50 font-bold">Sport / Discipline</td>
                    <td class="border p-2 uppercase font-bold text-blue-800">{{ $application->sport }}</td>
                </tr>
                <tr>
                    <td class="border p-2 bg-gray-50 font-bold">Previous School</td>
                    <td class="border p-2">{{ $application->previous_school }} ({{ $application->school_type }})</td>
                </tr>
            </table>
        </div>

        {{-- GUARDIAN --}}
        <div class="mb-8">
            <h3 class="bg-gray-800 text-white px-2 py-1 font-bold uppercase text-sm mb-2">III. Guardian Information</h3>
            <table class="w-full text-sm border-collapse border border-gray-300">
                <tr>
                    <td class="border p-2 bg-gray-50 w-1/4 font-bold">Name</td>
                    <td class="border p-2">{{ $application->guardian_name }}</td>
                </tr>
                <tr>
                    <td class="border p-2 bg-gray-50 font-bold">Contact No.</td>
                    <td class="border p-2">{{ $application->guardian_contact }}</td>
                </tr>
            </table>
        </div>

        {{-- SIGNATURE AREA --}}
        <div class="mt-12 flex justify-between text-center text-sm">
            <div class="w-1/3">
                <div class="border-b border-black mb-2"></div>
                <p class="font-bold uppercase">{{ $application->first_name }} {{ $application->last_name }}</p>
                <p class="text-xs">Applicant Signature</p>
            </div>
            <div class="w-1/3">
                <div class="border-b border-black mb-2"></div>
                <p class="font-bold uppercase">{{ $application->guardian_name }}</p>
                <p class="text-xs">Guardian Signature</p>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="absolute bottom-4 left-0 w-full text-center text-xs text-gray-400">
            <p>System Generated Form | Date Printed: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

    </div>

    {{-- AUTO PRINT SCRIPT (Optional) --}}
    <script>
        // Uncomment kung gusto mo mag-open agad ang print dialog
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>