<x-app-layout>
    {{-- Font Style Override --}}
    <style>
        .font-poppins-override * { font-family: 'Poppins', sans-serif !important; }
        /* Transition effect para smooth ang pagkawala */
        .fade-transition { transition: opacity 0.3s ease-in-out; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center font-poppins-override"
             x-data="{ showButton: true }" 
             @hide-add-button.window="showButton = false" 
             @show-add-button.window="showButton = true">
            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sections & Classes') }}
            </h2>

            {{-- 👇 ADD SECTION BUTTON --}}
            {{-- Ito ay magha-hide kapag narinig niya ang 'hide-add-button' event --}}
            <button x-show="showButton"
                    x-transition
                    onclick="document.getElementById('trigger-create-hidden').click()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition duration-150 ease-in-out cursor-pointer">
                Add Section
            </button>
        </div>
    </x-slot>

    <div class="py-12 font-poppins-override">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Livewire Component --}}
            @livewire('sections-manager')
        </div>
    </div>
</x-app-layout>