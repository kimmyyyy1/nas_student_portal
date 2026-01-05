<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Awards & Recognition') }}
            </h2>
            <a href="{{ route('awards.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-bold text-sm shadow">
                + Add Award
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($awards->isEmpty())
                        <p class="text-center text-gray-500 py-10">No awards recorded yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase">Student</th>
                                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase">Award Name</th>
                                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase">Category</th>
                                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase">Date Received</th>
                                        <th class="px-6 py-3 text-right font-bold text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($awards as $award)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $award->student->last_name }}, {{ $award->student->first_name }}
                                            </td>
                                            <td class="px-6 py-4 font-bold text-indigo-700">
                                                {{ $award->award_name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                                    {{ $award->category == 'Academic' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $award->category == 'Sports' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $award->category == 'Special' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ $award->category }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                {{ date('M d, Y', strtotime($award->date_received)) }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('awards.edit', $award->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-2">Edit</a>
                                                <form action="{{ route('awards.destroy', $award->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this award record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                                </form>
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