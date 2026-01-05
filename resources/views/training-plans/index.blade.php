<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Training Plans') }}
            </h2>
            <a href="{{ route('training-plans.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Plan</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Plan Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Team</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Duration</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($trainingPlans as $plan)
                                <tr>
                                    <td class="px-6 py-4">{{ $plan->plan_name }}</td>
                                    <td class="px-6 py-4">{{ $plan->team->team_name ?? 'General' }}</td>
                                    <td class="px-6 py-4">{{ date('M d', strtotime($plan->start_date)) }} - {{ date('M d, Y', strtotime($plan->end_date)) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('training-plans.edit', $plan->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form class="inline-block" method="POST" action="{{ route('training-plans.destroy', $plan->id) }}" onsubmit="return confirm('Delete this plan?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No training plans found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>