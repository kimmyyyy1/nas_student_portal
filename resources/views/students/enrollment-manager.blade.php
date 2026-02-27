<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title flex border-none">
            {{ __('Enrollment System Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Inbox: Incoming Enrollees (Requirements Submitted)
                        </h3>
                        <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            {{ $pendingEnrollees->count() }} Pending
                        </span>
                    </div>
                    
                    @if($pendingEnrollees->isEmpty())
                        <div class="p-8 text-center bg-gray-50 rounded border border-dashed border-gray-300 text-gray-500">
                            No pending enrollment applications.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-orange-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-bold text-orange-900 uppercase">Applicant</th>
                                        <th class="px-4 py-3 text-left font-bold text-orange-900 uppercase">Grade & Sport</th>
                                        <th class="px-4 py-3 text-left font-bold text-orange-900 uppercase">Files</th>
                                        <th class="px-4 py-3 text-left font-bold text-orange-900 uppercase">Date</th>
                                        <th class="px-4 py-3 text-center font-bold text-orange-900 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingEnrollees as $app)
                                        <tr class="hover:bg-orange-50/30 transition">
                                            <td class="px-4 py-3 align-top">
                                                <div class="font-bold text-gray-900">{{ $app->last_name }}, {{ $app->first_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $app->email_address }}</div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                {{ $app->grade_level_applied }} <br>
                                                <span class="text-xs text-indigo-600 font-semibold">{{ $app->sport }}</span>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                @if(!empty($app->enrollment_files))
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($app->enrollment_files as $key => $path)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-red-500 italic text-xs">None</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-top text-gray-600">
                                                {{ $app->updated_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-center align-middle">
                                                <a href="{{ route('students.process_enrollment_view', $app->id) }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-bold text-xs text-white uppercase hover:bg-orange-700 shadow">
                                                    Process Enrollment
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

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Official Enrollment Records (LIS Monitoring)
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-blue-800 uppercase">Student Name</th>
                                    <th class="px-4 py-3 text-left font-bold text-blue-800 uppercase w-32">Status</th>
                                    <th class="px-4 py-3 text-left font-bold text-blue-800 uppercase w-40">Enrollment Date</th>
                                    <th class="px-4 py-3 text-left font-bold text-blue-800 uppercase w-32">LIS Status</th>
                                    <th class="px-4 py-3 text-left font-bold text-blue-800 uppercase">Remarks</th>
                                    <th class="px-4 py-3 text-center font-bold text-blue-800 uppercase w-20">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <tr class="hover:bg-gray-50 transition">
                                        <form method="POST" action="{{ route('students.update_enrollment', $student->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <td class="px-4 py-2 align-middle">
                                                <div class="font-medium text-gray-900">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $student->nas_student_id }}</div>
                                            </td>
                                            
                                            <td class="px-4 py-2 align-middle">
                                                <select name="status" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-500 w-full py-1">
                                                    <option value="Enrolled" {{ $student->status == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                                                    <option value="New" {{ $student->status == 'New' ? 'selected' : '' }}>New</option>
                                                    <option value="Continuing" {{ $student->status == 'Continuing' ? 'selected' : '' }}>Continuing</option>
                                                    <option value="Transfer out" {{ $student->status == 'Transfer out' ? 'selected' : '' }}>Transfer out</option>
                                                    <option value="Graduate" {{ $student->status == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                                </select>
                                            </td>

                                            <td class="px-4 py-2 align-middle">
                                                <input type="date" name="enrollment_date" class="text-xs rounded-md border-gray-300 shadow-sm w-full py-1 focus:border-indigo-500" 
                                                       value="{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '' }}">
                                            </td>

                                            <td class="px-4 py-2 align-middle">
                                                <select name="lis_status" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-500 w-full py-1">
                                                    <option value="Pending" {{ $student->lis_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Enrolled" {{ $student->lis_status == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                                                    <option value="For Follow-up" {{ $student->lis_status == 'For Follow-up' ? 'selected' : '' }}>For Follow-up</option>
                                                </select>
                                            </td>

                                            <td class="px-4 py-2 align-middle">
                                                <input type="text" name="enrollment_remarks" class="text-xs rounded-md border-gray-300 shadow-sm w-full py-1 focus:border-indigo-500" 
                                                       value="{{ $student->enrollment_remarks }}" placeholder="Remarks...">
                                            </td>

                                            <td class="px-4 py-2 text-center align-middle">
                                                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs font-bold uppercase shadow">SAVE</button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>