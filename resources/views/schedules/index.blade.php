<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1 font-poppins-override">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase shadow-sm border border-blue-200">
                <i class='bx bxs-calendar mr-1.5 text-sm'></i> Schedules
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex justify-between items-center w-full py-2 font-poppins-override">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Schedules') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
            
            {{-- 🟢 DESKTOP BUTTON (Hidden on Mobile) --}}
            <button id="desktop-add-btn"
                    onclick="this.style.display='none'; document.getElementById('hidden-create-btn').click()"
                    class="hidden md:flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm items-center justify-center shadow transition duration-150 ease-in-out">
                {{-- SVG Plus Icon --}}
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Schedule
            </button>
        </div>

    </x-slot>

    {{-- 👇 CONTENT BODY --}}
    <div class="py-2 md:py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible ONLY on Mobile) --}}
            <div class="block md:hidden mb-4" id="mobile-btn-container">
                <button id="mobile-add-btn"
                        onclick="document.getElementById('mobile-btn-container').style.display='none'; document.getElementById('hidden-create-btn').click()"
                        class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 text-white rounded-lg shadow-md font-bold text-sm hover:bg-blue-700 active:scale-95 transition-all">
                    
                    {{-- SVG Plus Icon --}}
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    
                    Add New Schedule
                </button>
            </div>

            {{-- Livewire Component --}}
            @livewire('schedules-manager')
        </div>
    </div>

    {{-- 👇 SCRIPT TO FIX BACK BUTTON ISSUE --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            var desktopBtn = document.getElementById('desktop-add-btn');
            var mobileContainer = document.getElementById('mobile-btn-container');

            if (desktopBtn) {
                // Ibalik sa 'flex' kung desktop size
                desktopBtn.style.display = ''; 
            }
            if (mobileContainer) {
                // Ibalik sa 'block' kung mobile size
                mobileContainer.style.display = '';
            }
        });
    </script>

</x-app-layout>