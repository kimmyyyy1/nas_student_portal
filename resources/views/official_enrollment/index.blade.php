<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrollment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notification --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {!! session('success') !!}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-gray-700 uppercase tracking-wide">
                            Applicants Pending Enrollment
                        </h3>
                        <span class="text-xs text-gray-500 italic">Showing applicants who submitted Official Enrollment Forms.</span>
                    </div>
                    
                    {{-- USE $enrollees variable from Controller --}}
                    @if($enrollees->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Enrollments</h3>
                            <p class="mt-1 text-sm text-gray-500">Wait for qualified applicants to submit their enrollment forms.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                        <th class="py-3 px-6 text-left whitespace-nowrap">App ID</th>
                                        <th class="py-3 px-6 text-left">Name</th>
                                        <th class="py-3 px-6 text-center whitespace-nowrap">LRN</th>
                                        <th class="py-3 px-6 text-center">Sport</th>
                                        <th class="py-3 px-6 text-center">Status</th>
                                        <th class="py-3 px-6 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @foreach($enrollees as $applicant)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                            {{-- App ID --}}
                                            <td class="py-3 px-6 text-left whitespace-nowrap font-bold text-indigo-600">
                                                {{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}
                                            </td>

                                            {{-- Name --}}
                                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                                <div class="font-bold text-gray-800 uppercase">{{ $applicant->last_name }}, {{ $applicant->first_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $applicant->email }}</div>
                                            </td>

                                            {{-- LRN --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded text-xs font-mono">
                                                    {{ $applicant->lrn }}
                                                </span>
                                            </td>

                                            {{-- Sport --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $applicant->sport }}
                                                </span>
                                            </td>

                                            {{-- Status --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                {{-- Updated Logic: Check for 'Officially Enrolled' --}}
                                                @if($applicant->status == 'Officially Enrolled')
                                                    <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-[10px] font-bold uppercase border border-yellow-200 animate-pulse">
                                                        For Verification
                                                    </span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase">
                                                        {{ $applicant->status }}
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Action Button --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                <a href="{{ route('official-enrollment.show', $applicant->id) }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded text-xs font-bold shadow transition transform hover:scale-105 whitespace-nowrap uppercase tracking-wide">
                                                    Verify & Enroll
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 px-4">
                            {{ $enrollees->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>