<x-app-layout>
    {{-- Global Styles --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
    </style>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center font-poppins-override">
            
            {{-- TITLE --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sections & Classes') }}
            </h2>

            {{-- 🟢 DESKTOP BUTTON (Hidden sa Mobile) --}}
            {{-- Ito ay lalabas lang kapag naka-PC/Laptop --}}
            <button onclick="document.getElementById('hidden-create-btn').click()"
                    class="hidden md:flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out cursor-pointer items-center">
                <i class='bx bx-plus mr-2 text-lg'></i> 
                Add Section
            </button>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible only on Mobile) --}}
            {{-- Ito ang lilitaw sa cellphone, nasa loob ng content para hindi matakpan --}}
            <div class="md:hidden mb-6">
                <button onclick="document.getElementById('hidden-create-btn').click()"
                        class="w-full flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md text-sm transition duration-150 ease-in-out cursor-pointer">
                    <i class='bx bx-plus mr-2 text-xl'></i> 
                    Add New Section
                </button>
            </div>

            {{-- Livewire Component --}}
            @livewire('sections-manager')
        </div>
    </div>
</x-app-layout>