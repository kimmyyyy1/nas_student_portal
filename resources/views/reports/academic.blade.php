<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title flex border-none">
            Academic Report for {{ $student->last_name }}, {{ $student->first_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-lg font-semibold mb-2">Student Information</h3>
                <p><strong>Student No.:</strong> {{ $student->student_number }}</p>
                <p><strong>Section:</strong> {{ optional($student->section)->grade_level }} - {{ optional($student->section)->section_name }}</p>
            </div>

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                    <h3 class="text-lg font-semibold mb-4">Grades Breakdown</h3>
                    
                    @if ($student->grades->isEmpty())
                        <p class="text-gray-500">No grades recorded for this student yet.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grading Period</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mark</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($student->grades->sortByDesc('created_at') as $grade)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $grade->schedule->subject->subject_name ?? 'N/A' }} ({{ $grade->schedule->subject->subject_code ?? '' }})</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($grade->schedule->staff)->last_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $grade->grading_period }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-lg @if($grade->mark < 75) text-red-600 @endif">
                                        {{ $grade->mark }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>