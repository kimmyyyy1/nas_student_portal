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
                    <h3 class="font-bold text-lg mb-4 text-gray-700 uppercase tracking-wide">Qualified Applicants List</h3>
                    
                    @if($qualifiedApplicants->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500">No qualified applicants waiting for enrollment.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                        <th class="py-3 px-6 text-left whitespace-nowrap">App ID</th>
                                        <th class="py-3 px-6 text-left">Name</th>
                                        <th class="py-3 px-6 text-center whitespace-nowrap">Grade Level</th>
                                        <th class="py-3 px-6 text-center">Sport</th>
                                        <th class="py-3 px-6 text-center">Status</th>
                                        <th class="py-3 px-6 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @foreach($qualifiedApplicants as $applicant)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            {{-- App ID --}}
                                            <td class="py-3 px-6 text-left whitespace-nowrap font-bold text-indigo-600">
                                                {{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}
                                            </td>

                                            {{-- Name --}}
                                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                                <div class="font-bold text-gray-800">{{ $applicant->last_name }}, {{ $applicant->first_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $applicant->email_address }}</div>
                                            </td>

                                            {{-- Grade Level --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                <span class="bg-gray-200 text-gray-700 py-1 px-3 rounded-full text-xs font-bold">
                                                    {{ $applicant->grade_level_applied }}
                                                </span>
                                            </td>

                                            {{-- Sport --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                {{ $applicant->sport }}
                                            </td>

                                            {{-- Status --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold uppercase">
                                                    Qualified
                                                </span>
                                            </td>

                                            {{-- Action Button (Fixed Wrapping) --}}
                                            <td class="py-3 px-6 text-center whitespace-nowrap">
                                                <a href="{{ route('official-enrollment.show', $applicant->id) }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded text-xs font-bold shadow transition transform hover:scale-105 whitespace-nowrap">
                                                    PROCESS ENROLLMENT
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>