<dialog id="main-modal"
        class="w-[90%] max-w-5xl bg-transparent rounded mx-auto p-0 animate-fade-in fixed ring-0 outline-none">
    <div data-content></div>
    <div data-loading class="p-5">
        <img src="{{ asset('img/carregando-modal.png') }}" class="w-1/4 mx-auto animate-spin-slow" alt="">
        <div class="py-3 text-center font-medium text-xl text-white">Carregando...</div>
    </div>
</dialog>
<dialog id="alert-modal"
        class="min-w-[300px] w-fit max-w-2xl mx-auto bg-white shadow rounded-xl py-6 px-10 animate-fade-in-fast">
    <div class="flex flex-col space-y-4">
        <div class="flex justify-center">
            <div data-icon-success>
                <x-heroicon-s-check-circle class="text-emerald-500 w-14 h-14 animate-pulse-once" />
            </div>
            <div data-icon-warning>
                <x-heroicon-s-exclamation-circle class="text-amber-400 w-14 h-14 animate-pulse-once" />
            </div>
            <div data-icon-error>
                <x-heroicon-s-x-circle class="text-red-400 w-14 h-14 animate-wiggle-once" />
            </div>
            <div data-icon-info>
                <x-heroicon-s-information-circle class="text-sky-400 w-14 h-14 animate-wiggle-once" />
            </div>
        </div>
        <div class="flex flex-col space-y-1">
            <div data-title class="text-center font-medium text-2xl text-gray-600"></div>
            <div data-content class="text-center text-lg text-gray-500"></div>
        </div>
    </div>

    <div class="text-center mt-8">
        <x-forms.button onclick="Modal.close('alert-modal')">fechar</x-forms.button>
    </div>

</dialog>
