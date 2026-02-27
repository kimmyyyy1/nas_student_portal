<x-app-layout>
    {{-- Global Styles --}}
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">
                {{ __('Sports and Teams') }}
            </h2>
            
            {{-- 🟢 DESKTOP ADD BUTTON (Hidden on Mobile) --}}
            <a href="{{ route('teams.create') }}" class="hidden md:inline-flex premium-btn-primary gap-2">
                {{-- SVG Plus Icon --}}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Team
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        {{-- Added px-4 for spacing on mobile --}}
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible only on Mobile) --}}
            <div class="md:hidden mb-6">
                <a href="{{ route('teams.create') }}" class="w-full justify-center premium-btn-primary gap-2 mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Team
                </a>
            </div>

            {{-- Notification --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 border-b border-white/40">
                    
                    {{-- Header Row (Optional secondary header inside box) --}}
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg text-gray-700">List of Focus Sports</h3>
                    </div>

                    <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                        <table class="min-w-full divide-y divide-gray-100/50 bg-transparent">
                            <thead class="premium-table-header">
                                <tr>
                                    <th>FOCUS SPORTS</th>
                                    <th>Coach</th>
                                    <th class="relative text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/50">
                                @forelse ($teams as $team)
                                    <tr class="premium-table-row group">
                                        <td class="premium-table-cell">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold tracking-widest uppercase rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ $team->sport }}
                                            </span>
                                        </td>
                                        
                                        <td class="premium-table-cell font-bold text-[13px] text-slate-800">
                                            {{ $team->coach_name ?? 'N/A' }}
                                        </td>
                                        
                                        <td class="premium-table-cell text-right text-[13px] font-bold">
                                            
                                            <a href="{{ route('teams.edit', $team->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">Edit</a>
                                            
                                            <form class="inline-block" method="POST" action="{{ route('teams.destroy', $team->id) }}" onsubmit="return confirm('Sigurado ka bang gusto mong burahin ang team na ito?');">
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
                                        {{-- Updated colspan to 3 since a column was removed --}}
                                        <td colspan="3" class="px-6 py-10 whitespace-nowrap text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                {{-- Using a generic icon if bx icons aren't loaded, or keep bx if they are --}}
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                <p class="text-lg font-medium">No teams found.</p>
                                                <p class="text-sm">Click "Add Team" to get started.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($teams, 'links'))
                        <div class="mt-4">
                            {{ $teams->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>