<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase shadow-sm border border-indigo-200">
                <i class='bx bxs-book-content mr-1.5 text-sm'></i> Subject
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex justify-between items-center w-full py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Subjects') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
            
            {{-- 🟢 DESKTOP BUTTON (Hidden on Mobile) --}}
            <a href="{{ route('subjects.create') }}" 
               wire:navigate 
               id="desktop-add-btn"
               onclick="this.style.display='none'"
               class="hidden md:flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm items-center justify-center shadow transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Subject
            </a>
        </div>

    </x-slot>

    {{-- 👇 CONTENT BODY --}}
    <div class="py-2 md:py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible ONLY on Mobile) --}}
            <div class="block md:hidden mb-4" id="mobile-btn-container">
                <a href="{{ route('subjects.create') }}" 
                   wire:navigate
                   id="mobile-add-btn"
                   onclick="document.getElementById('mobile-btn-container').style.display='none'"
                   class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 text-white rounded-lg shadow-md font-bold text-sm hover:bg-blue-700 active:scale-95 transition-all">
                    <i class='bx bx-plus mr-2 text-xl'></i>
                    Add New Subject
                </a>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow relative text-sm">
                    <button onclick="this.parentElement.style.display='none'" class="absolute top-2 right-2 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{!! session('success') !!}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-0 md:p-6 text-gray-900">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 whitespace-nowrap">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subject Code</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subject Name</th>
                                    <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="relative px-4 md:px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($subjects as $subject)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $subject->subject_code }}
                                        </td>
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $subject->subject_name }}
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ Str::limit($subject->description, 50) ?: 'N/A' }}
                                        </td>
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('subjects.edit', $subject->id) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">Edit</a>
                                            
                                            <form class="inline-block" method="POST" action="{{ route('subjects.destroy', $subject->id) }}" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                                        <td colspan="4" class="px-6 py-12 whitespace-nowrap text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class='bx bx-book-content text-4xl text-gray-300 mb-2'></i>
                                                <p class="text-lg font-medium">No subjects found.</p>
                                                <p class="text-sm mt-1">Click "Add Subject" above to create one.</p>
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

    {{-- 👇 SCRIPT TO FIX BACK BUTTON ISSUE --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            var desktopBtn = document.getElementById('desktop-add-btn');
            var mobileContainer = document.getElementById('mobile-btn-container');

            if (desktopBtn) {
                // Ibalik sa 'flex' kung desktop size, pero dahil sa Tailwind classes na 'hidden md:flex',
                // pwede nating tanggalin lang ang inline style na display:none
                desktopBtn.style.display = ''; 
            }
            if (mobileContainer) {
                mobileContainer.style.display = ''; // Remove inline display:none
            }
        });
    </script>

</x-app-layout>