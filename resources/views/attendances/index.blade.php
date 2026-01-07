<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Checking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SAFE CHECK: Gamitin ang count() == 0 para safe sa Admin at Teacher --}}
            @if(count($sections) == 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                    <i class='bx bx-calendar-x text-6xl text-gray-300 mb-4'></i>
                    <h3 class="text-lg font-medium text-gray-900">No Classes Assigned</h3>
                    <p class="text-gray-500">You do not have any advisory class assigned yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sections as $section)
                        <a href="{{ route('attendances.show', $section->id) }}" class="block group">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-300 border border-gray-200 h-full">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                                            <i class='bx bx-calendar-check text-2xl'></i>
                                        </div>
                                        
                                        {{-- LOGIC: Ipakita ang Adviser Name kung Admin ang user --}}
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'registrar')
                                            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded-full uppercase truncate max-w-[120px]" title="Adviser">
                                                <i class='bx bx-user'></i> {{ $section->adviser->last_name ?? 'No Adviser' }}
                                            </span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full uppercase">
                                                Advisory
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition">
                                        {{ $section->grade_level }} - {{ $section->section_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class='bx bx-user'></i> Students: {{ $section->students->count() }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <i class='bx bx-map'></i> Room: {{ $section->room_number ?? 'TBA' }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium">CHECK ATTENDANCE</span>
                                    <i class='bx bx-right-arrow-alt text-blue-600 transform group-hover:translate-x-1 transition'></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>