<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Track Application Status | NASCENT SAS</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0">
            
            <div class="w-full max-w-md p-6 text-center">
                <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" class="h-20 mx-auto mb-4" alt="NAS Logo">
                <h1 class="text-2xl font-bold text-gray-800">NASCENT SAS Tracker</h1>
            </div>

            <div class="w-full max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
                
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('status_result'))
                    <div class="mb-6 p-6 bg-indigo-50 border border-indigo-200 rounded text-center">
                        <p class="text-sm text-gray-500 mb-1">Applicant Name</p>
                        <h3 class="font-bold text-lg text-gray-800">{{ session('status_result')->last_name }}, {{ session('status_result')->first_name }}</h3>
                        
                        <div class="my-4 border-t border-indigo-200"></div>

                        <p class="text-sm text-gray-500 mb-1">Current Status</p>
                        
                        @php
                            // Logic para sa status color
                            $status = session('status_result')->status;
                            $statusClass = 'bg-blue-600 text-white'; // Default

                            if ($status == 'Qualified') {
                                $statusClass = 'bg-green-600 text-white';
                            } elseif ($status == 'Not Qualified' || $status == 'Rejected') {
                                $statusClass = 'bg-red-600 text-white';
                            } elseif ($status == 'Waitlisted') {
                                $statusClass = 'bg-orange-500 text-white';
                            }
                        @endphp

                        <span class="px-4 py-2 rounded-full text-sm font-bold inline-block shadow-sm {{ $statusClass }}">
                            {{ $status }}
                        </span>

                        <p class="text-xs text-gray-400 mt-4">Last Updated: {{ session('status_result')->updated_at->format('M d, Y') }}</p>
                    </div>
                    
                    <a href="{{ route('admission.track') }}" class="block w-full text-center text-indigo-600 hover:underline text-sm">Check Another</a>
                @else
                    <form method="POST" action="{{ route('admission.check') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enter Registered Email Address</label>
                            <input type="email" name="email_address" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="student@example.com">
                            @error('email_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                            Check Status
                        </button>
                    </form>
                    
                    <div class="mt-6 text-center border-t pt-4">
                        <a href="{{ route('admission.form') }}" class="text-sm text-gray-500 hover:text-gray-900">Submit New Application</a>
                    </div>
                @endif

            </div>
        </div>
    </body>
</html>