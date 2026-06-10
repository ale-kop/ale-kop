<x-layout title="Contato">

    <div class="px-6 lg:px-8 pb-24 max-w-7xl mx-auto">
        <div class="max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="mb-12 max-w-2xl">
                <p class="font-mono text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">Fale comigo</p>
                <h1 class="font-serif text-4xl font-medium tracking-tight text-gray-950 sm:text-5xl">
                    Entre em contato
                </h1>
                <p class="mt-4 text-lg text-gray-600 font-serif">
                    Tem uma dúvida, proposta ou só quer trocar uma ideia? Aqui você pode falar comigo
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_480px] gap-16">

                {{-- Left: social links + info --}}
                <div class="space-y-10">

                    {{-- <div class="space-y-3">
                        <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-widest">E-mail direto</h2>
                        <a href="mailto:contato@alekop.com"
                           class="inline-flex items-center gap-2 text-gray-950 font-medium hover:text-brand transition-colors">
                            <x-heroicon-o-envelope class="w-5 h-5 shrink-0 text-gray-400"/>
                            contato@alekop.com
                        </a>
                    </div> --}}

                    <div class="space-y-4">
                        <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Redes sociais</h2>

                        <a href="https://www.linkedin.com/in/alekop/"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-brand/20 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-[#0077B5]/10 flex items-center justify-center shrink-0">
                                <svg viewBox="0 0 16 16" fill="currentColor" class="size-5 text-[#0077B5]">
                                    <path d="M14.82 0H1.18A1.169 1.169 0 000 1.154v13.694A1.168 1.168 0 001.18 16h13.64A1.17 1.17 0 0016 14.845V1.15A1.171 1.171 0 0014.82 0zM4.744 13.64H2.369V5.996h2.375v7.644zm-1.18-8.684a1.377 1.377 0 11.52-.106 1.377 1.377 0 01-.527.103l.007.003zm10.075 8.683h-2.375V9.921c0-.885-.015-2.025-1.234-2.025-1.218 0-1.425.966-1.425 1.968v3.775H6.233V5.997H8.51v1.05h.032c.317-.601 1.09-1.235 2.246-1.235 2.405-.005 2.851 1.578 2.851 3.63v4.197z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 group-hover:text-brand transition-colors">LinkedIn</p>
                                <p class="text-sm text-gray-500">Aleksandr Kopelevitch</p>
                            </div>
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-300 group-hover:text-brand/50 ml-auto transition-colors"/>
                        </a>

                        <a href="https://x.com/alekop_com"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-gray-900/20 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                                <svg viewBox="0 0 16 16" fill="currentColor" class="size-4 text-gray-900">
                                    <path d="M12.6 0h2.454l-5.36 6.778L16 16h-4.937l-3.867-5.594L2.771 16H.316l5.733-7.25L0 0h5.063l3.495 5.114L12.6 0zm-.86 14.376h1.36L4.323 1.539H2.865l8.875 12.837z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">X (Twitter)</p>
                                <p class="text-sm text-gray-500">@alekop</p>
                            </div>
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-300 group-hover:text-gray-400 ml-auto transition-colors"/>
                        </a>

                        <a href="https://www.instagram.com/alekop.pro/"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-[#E1306C]/20 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-[#E1306C]/10 flex items-center justify-center shrink-0">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="size-5 text-[#E1306C]">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 group-hover:text-[#E1306C] transition-colors">Instagram</p>
                                <p class="text-sm text-gray-500">@alekop.pro</p>
                            </div>
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-300 group-hover:text-[#E1306C]/50 ml-auto transition-colors"/>
                        </a>

                        <a href="https://www.youtube.com/@alekopcom"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md hover:border-[#FF0000]/20 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-[#FF0000]/10 flex items-center justify-center shrink-0">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="size-5 text-[#FF0000]">
                                    <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 group-hover:text-[#FF0000] transition-colors">YouTube</p>
                                <p class="text-sm text-gray-500">@alekopcom</p>
                            </div>
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-300 group-hover:text-[#FF0000]/50 ml-auto transition-colors"/>
                        </a>
                    </div>

                </div>

                {{-- Right: contact form --}}
                <div>
                    <form id="contact-form" class="space-y-5">
                        @csrf

                        {{-- Honeypot --}}
                        <div class="hidden" aria-hidden="true">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <x-forms.label for="contact-name">Nome</x-forms.label>
                                <x-forms.input id="contact-name" name="name" type="text"
                                               placeholder="Seu nome" autocomplete="name" required/>
                            </div>
                            <div>
                                <x-forms.label for="contact-email">E-mail</x-forms.label>
                                <x-forms.input id="contact-email" name="email" type="email"
                                               placeholder="voce@exemplo.com" autocomplete="email" required/>
                            </div>
                        </div>

                        {{-- <div>
                            <x-forms.label for="contact-subject">Assunto</x-forms.label>
                            <x-forms.input id="contact-subject" name="subject" type="text"
                                           placeholder="Em poucas palavras, sobre o que é?" required/>
                        </div> --}}

                        <div>
                            <x-forms.label for="contact-message">Mensagem</x-forms.label>
                            <x-forms.textarea id="contact-message" name="message" rows="6"
                                              placeholder="Escreva sua mensagem aqui..." required/>
                        </div>

                        <x-forms.button
                            data-ak-ajax="contact-form"
                            data-ak-action="{{ route('contact.send') }}"
                            class="w-full !bg-gray-950 hover:!bg-gray-800 !py-3 !text-base"
                            submit-button>
                            Enviar mensagem
                        </x-forms.button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</x-layout>
