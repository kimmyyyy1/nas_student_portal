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
            <h2 class="page-title">
                {{ __('Subjects') }}
                <span class="ml-4 px-2.5 py-1 rounded-md text-[10px] font-black tracking-widest bg-rose-100 text-rose-600 animate-pulse flex items-center shadow-sm border border-rose-200">
                    <span class="w-1.5 h-1.5 bg-rose-600 rounded-full mr-1.5"></span> LIVE
                </span>
            </h2>
            
            {{-- 🟢 DESKTOP BUTTON (Hidden on Mobile) --}}
            <a href="{{ route('subjects.create') }}" 
               wire:navigate 
               id="desktop-add-btn"
               onclick="this.style.display='none'"
               class="hidden md:flex premium-btn-primary">
                {{-- SVG Plus Icon --}}
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Subject
            </a>
        </div>

    </x-slot>

    {{-- 👇 CONTENT BODY --}}
    <div class="py-4 md:py-8 font-poppins-override">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible ONLY on Mobile) --}}
            <div class="block md:hidden mb-4" id="mobile-btn-container">
                <a href="{{ route('subjects.create') }}" 
                   wire:navigate
                   id="mobile-add-btn"
                   onclick="document.getElementById('mobile-btn-container').style.display='none'"
                   class="w-full justify-center premium-btn-primary gap-2">
                    
                    {{-- 👇 FIXED: Pinalitan ang 'bx' font ng SVG para siguradong lumabas --}}
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    
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

            {{-- REMOVED DUPLICATE TABLE THAT WAS HARDCODED IN REPLACEMENT FOR LIVEWIRE COMPONENT --}}
            @livewire('subjects-manager')
        </div>
    </div>

    {{-- 👇 SCRIPT TO FIX BACK BUTTON ISSUE --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            var desktopBtn = document.getElementById('desktop-add-btn');
            var mobileContainer = document.getElementById('mobile-btn-container');

            if (desktopBtn) {
                desktopBtn.style.display = ''; 
            }
            if (mobileContainer) {
                mobileContainer.style.display = '';
            }
        });
    </script>

</x-app-layout>