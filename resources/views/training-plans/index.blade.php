<x-app-layout>
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view, including buttons */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">
                {{ __('Training Plans') }}
            </h2>
            
            {{-- 🟢 DESKTOP BUTTON (Hidden on Mobile) --}}
            <a href="{{ route('training-plans.create') }}" class="hidden md:inline-flex premium-btn-primary gap-2">
                {{-- SVG Plus Icon --}}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Plan
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        {{-- Added px-4 para hindi dikit sa gilid pag mobile --}}
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible only on Mobile) --}}
            <div class="md:hidden mb-6">
                <a href="{{ route('training-plans.create') }}" class="w-full justify-center premium-btn-primary gap-2 mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Plan
                </a>
            </div>

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 border-b border-white/40">
                    
                    <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                        <table class="min-w-full divide-y divide-gray-100/50 bg-transparent">
                            <thead class="premium-table-header">
                                <tr>
                                    <th class="premium-table-header-cell w-1/3 text-left">PLAN NAME</th>
                                    <th class="premium-table-header-cell w-1/4 text-left">FOCUS SPORT</th>
                                    <th class="premium-table-header-cell w-1/4 text-left">DURATION</th>
                                    <th class="premium-table-header-cell w-1/6 text-left">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/50">
                                @forelse ($trainingPlans as $plan)
                                    <tr class="premium-table-row group">
                                        <td class="premium-table-cell font-bold text-slate-800 uppercase tracking-wide">{{ $plan->plan_name }}</td>
                                        <td class="premium-table-cell">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold tracking-widest uppercase rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                {{-- Displaying the Sport from the relationship --}}
                                                {{ $plan->team->sport ?? ($plan->team->team_name ?? 'General') }}
                                            </span>
                                        </td>
                                        <td class="premium-table-cell font-bold text-[13px] text-slate-600">
                                            {{ date('M d', strtotime($plan->start_date)) }} - {{ date('M d, Y', strtotime($plan->end_date)) }}
                                        </td>
                                        <td class="premium-table-cell text-left text-[13px] font-bold">
                                            
                                            <a href="{{ route('training-plans.edit', $plan->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">Edit</a>
                                            
                                            <form class="inline-block" method="POST" action="{{ route('training-plans.destroy', $plan->id) }}" onsubmit="return confirm('Delete this plan?');">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition">
                                                    Delete
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 whitespace-nowrap text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class='bx bx-clipboard text-4xl text-gray-300 mb-2'></i>
                                                <p class="text-lg font-medium">No training plans found.</p>
                                                <p class="text-sm">Click "Create Plan" to start.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>