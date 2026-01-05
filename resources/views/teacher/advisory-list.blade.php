<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Class Masterlist') }} | SY {{ date('Y') }}-{{ date('Y')+1 }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        showModal: false, 
        studentName: '', studentId: '', currentGrade: '', currentStatus: '',
        openGradeModal(id, name, grade, status) {
            this.studentId = id; this.studentName = name; this.currentGrade = grade; this.currentStatus = status;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800">{{ $section->grade_level }} - {{ $section->section_name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Adviser: <span class="font-bold uppercase text-gray-700">{{ Auth::user()->name }}</span></p>
                    <p class="text-sm text-gray-500">Room: <span class="font-bold text-gray-700">{{ $section->room_number ?? 'TBA' }}</span></p>
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center bg-green-100 text-green-800 text-sm font-bold px-4 py-2 rounded-full shadow-sm">
                        Total Learners: {{ $students->count() }}
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-b-lg overflow-hidden">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-sm">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold text-gray-500 uppercase w-12">#</th>
                                <th class="px-6 py-4 text-left font-bold text-gray-500 uppercase">Learner Name</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase">Gender</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase">Grade</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $index => $student)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 text-gray-500 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 uppercase">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</div>
                                        <div class="text-xs text-blue-600 font-mono font-bold mt-1">{{ $student->lrn }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600">{{ substr($student->sex, 0, 1) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($student->general_average)
                                            <span class="font-extrabold text-gray-800 {{ $student->general_average < 75 ? 'text-red-600' : 'text-green-700' }}">{{ $student->general_average }}</span>
                                        @else <span class="text-gray-400 italic text-xs">N/A</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($student->promotion_status)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ str_contains($student->promotion_status, 'Promoted') ? 'bg-green-100 text-green-800' : ($student->promotion_status == 'Retained' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $student->promotion_status }}
                                            </span>
                                        @else <span class="text-gray-400 text-xs">-</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="openGradeModal('{{ $student->id }}', '{{ $student->last_name }}, {{ $student->first_name }}', '{{ $student->general_average }}', '{{ $student->promotion_status }}')" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-100 p-2 rounded-full transition" title="Edit Grade">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-indigo-600 px-4 py-3 sm:px-6"><h3 class="text-lg font-bold leading-6 text-white">Update Learner Status</h3></div>
                    <form :action="'/my-advisory-class/' + studentId + '/update'" method="POST">
                        @csrf @method('PATCH')
                        <div class="px-4 py-5 sm:p-6">
                            <div class="mb-6"><p class="text-xs text-gray-500 uppercase font-bold">Updating record for:</p><h4 class="text-xl font-extrabold text-gray-900 mt-1" x-text="studentName"></h4></div>
                            <div class="mb-4"><label class="block text-sm font-bold text-gray-700 mb-1">Grade</label><input type="number" step="0.01" name="general_average" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" x-model="currentGrade" placeholder="e.g. 92.5"></div>
                            <div class="mb-4"><label class="block text-sm font-bold text-gray-700 mb-1">Promotion Status</label><select name="promotion_status" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" x-model="currentStatus"><option value="">-- Select --</option><option value="Promoted">Promoted</option><option value="Promoted with Honors">Promoted with Honors</option><option value="Promoted with High Honors">Promoted with High Honors</option><option value="Promoted with Highest Honors">Promoted with Highest Honors</option><option value="Conditional">Conditional</option><option value="Retained">Retained</option></select></div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 flex justify-end gap-3 sm:px-6">
                            <button type="button" @click="showModal = false" class="rounded-md bg-white px-4 py-2 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-500">Update Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>