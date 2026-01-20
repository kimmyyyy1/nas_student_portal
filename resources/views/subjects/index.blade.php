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
                {{ __('Subjects') }}
            </h2>

            {{-- 🟢 DESKTOP BUTTON (Hidden on Mobile) --}}
            {{-- Ito ay lalabas lang kapag naka-PC/Laptop --}}
            <button onclick="document.getElementById('hidden-create-btn').click()"
                    class="hidden md:flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out cursor-pointer items-center">
                {{-- SVG Plus Icon --}}
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Subject
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
                    {{-- SVG Plus Icon --}}
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Subject
                </button>
            </div>

            {{-- Livewire Component --}}
            @livewire('subjects-manager')
        </div>
    </div>
</x-app-layout>