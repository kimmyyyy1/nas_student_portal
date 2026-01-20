<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- HEADER --}}
    <x-slot name="header">
        {{-- 👇 FIX: Ginawang Column sa Mobile (flex-col), Row sa Desktop (md:flex-row) --}}
        {{-- Added 'gap-4' para may space pag nag-stack --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 font-poppins-override"
             x-data="{ showButton: true }"
             @toggle-add-button.window="showButton = $event.detail.show">
            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sections & Classes') }}
            </h2>

            {{-- 👇 FIX: Button Width Adjusted --}}
            {{-- w-full (Mobile): Para madaling pindutin at nasa ilalim ng title --}}
            {{-- md:w-auto (Desktop): Para bumalik sa dating laki sa kanan --}}
            <button x-show="showButton"
                    x-cloak
                    onclick="document.getElementById('hidden-create-btn').click()"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out cursor-pointer flex justify-center items-center">
                <i class='bx bx-plus mr-2 text-lg'></i> Add Section
            </button>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 font-poppins-override">
        {{-- Added px-4 para hindi dikit sa gilid ng cellphone --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            {{-- Livewire Component --}}
            @livewire('sections-manager')
        </div>
    </div>
</x-app-layout>