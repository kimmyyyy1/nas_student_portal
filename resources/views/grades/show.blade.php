<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('grades.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-arrow-back text-2xl'></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grading Sheet') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('grades.bulk_update') }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- HEADER & BUTTON --}}
                <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200 p-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-extrabold text-indigo-700">{{ $section->grade_level }} - {{ $section->section_name }}</h1>
                        <p class="text-sm text-gray-500">Adviser: {{ Auth::user()->name }}</p>
                    </div>
                    
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center gap-2 transition">
                        <i class='bx bx-save text-xl'></i> SAVE ALL GRADES
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="bg-white shadow-sm sm:rounded-b-lg overflow-x-auto">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-sm font-bold m-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-300 text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">#</th>
                                <th class="px-4 py-3 text-left">LEARNER NAME</th>
                                <th class="px-2 py-3 text-center w-24 bg-gray-700">Q1</th>
                                <th class="px-2 py-3 text-center w-24 bg-gray-700">Q2</th>
                                <th class="px-2 py-3 text-center w-24 bg-gray-700">Q3</th>
                                <th class="px-2 py-3 text-center w-24 bg-gray-700">Q4</th>
                                <th class="px-4 py-3 text-center w-24 font-bold bg-gray-900">FINAL</th>
                                <th class="px-4 py-3 text-center w-32">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($section->students as $index => $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 text-center text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">
                                        <div class="font-bold text-gray-800 text-base">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                        <div class="text-xs text-indigo-600 font-mono">{{ $student->lrn }}</div>
                                    </td>
                                    <td class="p-2"><input type="number" step="0.01" name="grades[{{ $student->id }}][q1]" value="{{ $student->q1 }}" class="w-full text-center border-gray-300 rounded shadow-sm focus:ring-indigo-500 grade-input font-mono" data-id="{{ $student->id }}"></td>
                                    <td class="p-2"><input type="number" step="0.01" name="grades[{{ $student->id }}][q2]" value="{{ $student->q2 }}" class="w-full text-center border-gray-300 rounded shadow-sm focus:ring-indigo-500 grade-input font-mono" data-id="{{ $student->id }}"></td>
                                    <td class="p-2"><input type="number" step="0.01" name="grades[{{ $student->id }}][q3]" value="{{ $student->q3 }}" class="w-full text-center border-gray-300 rounded shadow-sm focus:ring-indigo-500 grade-input font-mono" data-id="{{ $student->id }}"></td>
                                    <td class="p-2"><input type="number" step="0.01" name="grades[{{ $student->id }}][q4]" value="{{ $student->q4 }}" class="w-full text-center border-gray-300 rounded shadow-sm focus:ring-indigo-500 grade-input font-mono" data-id="{{ $student->id }}"></td>
                                    <td class="p-2 text-center">
                                        <input type="text" readonly id="final-{{ $student->id }}" value="{{ $student->general_average ? number_format($student->general_average, 2) : '' }}" class="w-full text-center font-extrabold bg-gray-100 border-transparent text-gray-800 rounded">
                                    </td>
                                    <td class="p-2">
                                        <select name="grades[{{ $student->id }}][status]" id="status-{{ $student->id }}" class="w-full text-xs border-gray-300 rounded focus:ring-indigo-500">
                                            <option value="">-</option>
                                            <option value="Promoted" {{ $student->promotion_status == 'Promoted' ? 'selected' : '' }}>Promoted</option>
                                            <option value="Retained" {{ $student->promotion_status == 'Retained' ? 'selected' : '' }}>Retained</option>
                                            <option value="Conditional" {{ $student->promotion_status == 'Conditional' ? 'selected' : '' }}>Conditional</option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-10 text-gray-400">No students found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    {{-- Javascript for Computation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.grade-input');
            inputs.forEach(input => {
                input.addEventListener('input', function() { computeAverage(this.getAttribute('data-id')); });
            });
            function computeAverage(id) {
                const q1 = parseFloat(document.querySelector(`input[name="grades[${id}][q1]"]`).value);
                const q2 = parseFloat(document.querySelector(`input[name="grades[${id}][q2]"]`).value);
                const q3 = parseFloat(document.querySelector(`input[name="grades[${id}][q3]"]`).value);
                const q4 = parseFloat(document.querySelector(`input[name="grades[${id}][q4]"]`).value);
                
                let grades = [];
                if (!isNaN(q1)) grades.push(q1);
                if (!isNaN(q2)) grades.push(q2);
                if (!isNaN(q3)) grades.push(q3);
                if (!isNaN(q4)) grades.push(q4);

                const finalInput = document.getElementById(`final-${id}`);
                const statusSelect = document.getElementById(`status-${id}`);

                if (grades.length > 0) {
                    let divisor = (grades.length === 4) ? 4 : grades.length;
                    let avg = grades.reduce((a, b) => a + b, 0) / divisor;
                    finalInput.value = avg.toFixed(2);
                    if (grades.length === 4) {
                        statusSelect.value = avg >= 75 ? 'Promoted' : 'Retained';
                    }
                } else {
                    finalInput.value = '';
                }
            }
        });
    </script>
</x-app-layout>