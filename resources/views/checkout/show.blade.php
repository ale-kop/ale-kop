{{--
    Checkout transparente do Mercado Pago, no formato do Stripe Checkout:
    produto à esquerda, dados mínimos de pagamento à direita (cartão ou Pix).
    Cartão: Secure Fields do MercadoPago.js — número, validade e CVV são iframes do Mercado Pago.
    JS: resources/js/modules/checkout.js · Estilos: resources/css/components/checkout.css
--}}
<x-layouts.checkout :title="'Comprar '.$course->name">
<div class="checkout lg:grid lg:min-h-screen lg:grid-cols-2">

    {{-- ═══ Produto ═══════════════════════════════════════════════════ --}}
    <aside class="checkout-summary px-5 pb-8 pt-6 sm:px-10 lg:flex lg:justify-end lg:px-16 lg:pb-16 lg:pt-14">
        <div class="mx-auto w-full max-w-md lg:mx-0">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                <x-heroicon-m-arrow-left class="size-4"/>
                {{ config('app.name') }}
            </a>

            <div class="mt-8 lg:mt-14">
                <p class="text-[15px] font-medium text-gray-500">{{ $course->name }}</p>
                <p class="mt-1 text-4xl font-bold tracking-[-0.03em]">{{ $course->formattedPrice() }}</p>
                <p class="mt-1 text-sm text-gray-500">no Pix ou em até {{ $maxInstallments }}x no cartão</p>
            </div>

            <div class="mt-8 hidden items-start gap-4 lg:flex">
                <img src="{{ $image }}" alt="" class="size-16 flex-none rounded-lg object-cover ring-1 ring-black/5">
                <div class="min-w-0 flex-1">
                    <p class="text-[15px] font-semibold">{{ $course->name }}</p>
                    <p class="mt-0.5 text-sm text-gray-500">Curso online, acesso por 1 ano</p>
                </div>
                <p class="text-[15px] font-semibold">{{ $course->formattedPrice() }}</p>
            </div>

            <dl class="mt-8 hidden space-y-3 border-t border-black/10 pt-6 text-[15px] lg:block">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Subtotal</dt>
                    <dd>{{ $course->formattedPrice() }}</dd>
                </div>
                <div class="flex justify-between border-t border-black/10 pt-3 font-semibold">
                    <dt>Total</dt>
                    <dd>{{ $course->formattedPrice() }}</dd>
                </div>
            </dl>

            <ul class="mt-10 hidden space-y-2.5 text-sm text-gray-600 lg:block">
                <li class="flex gap-2.5"><x-heroicon-o-envelope class="size-5 flex-none text-gray-400"/>Acesso enviado por e-mail logo após a aprovação</li>
                <li class="flex gap-2.5"><x-heroicon-o-shield-check class="size-5 flex-none text-gray-400"/>7 dias para pedir reembolso, sem precisar explicar o motivo</li>
            </ul>
        </div>
    </aside>

    {{-- ═══ Pagamento ═════════════════════════════════════════════════ --}}
    <main class="px-5 pb-16 pt-8 sm:px-10 lg:px-16 lg:pt-14 lg:shadow-[-12px_0_32px_-24px_rgb(31_36_51/.25)]">
        <form class="mx-auto w-full max-w-md lg:mx-0" novalidate
              data-checkout
              data-pay-url="{{ route('checkout.pay', $course->slug) }}"
              data-public-key="{{ $publicKey }}"
              data-amount="{{ number_format($course->price(), 2, '.', '') }}"
              data-max-installments="{{ $maxInstallments }}">

            <h1 class="sr-only">Pagamento</h1>

            <fieldset>
                <legend class="text-base font-semibold">Seus dados</legend>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="email" class="checkout-label">E-mail</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ $user?->email }}" class="checkout-input" placeholder="voce@exemplo.com">
                        <p class="mt-1.5 text-[13px] text-gray-500">O acesso ao curso chega neste e-mail.</p>
                    </div>
                    <div>
                        <label for="name" class="checkout-label">Nome completo</label>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               value="{{ $user?->name }}" class="checkout-input">
                    </div>
                    <div>
                        <label for="document" class="checkout-label">CPF</label>
                        <input id="document" name="document" type="text" inputmode="numeric" autocomplete="off" required
                               class="checkout-input" placeholder="000.000.000-00" data-checkout-cpf>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mt-9">
                <legend class="text-base font-semibold">Forma de pagamento</legend>

                <div class="mt-4 grid grid-cols-2 gap-3" role="radiogroup">
                    <label class="checkout-method">
                        <input type="radio" name="payment_method" value="card" class="sr-only" checked>
                        <x-heroicon-o-credit-card class="size-5"/>
                        Cartão
                    </label>
                    <label class="checkout-method">
                        <input type="radio" name="payment_method" value="pix" class="sr-only">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2.8 21.2 12 12 21.2 2.8 12 12 2.8Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m7.6 9.4 2.9 2.9a2.1 2.1 0 0 0 3 0l2.9-2.9M7.6 14.6l2.9-2.9a2.1 2.1 0 0 1 3 0l2.9 2.9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        Pix
                    </label>
                </div>

                {{-- Cartão --}}
                <div class="mt-5 space-y-4" data-panel="card">
                    <div>
                        <span class="checkout-label" id="card-number-label">Número do cartão</span>
                        <div class="checkout-input checkout-field relative" aria-labelledby="card-number-label">
                            <div id="mp-card-number" class="h-full"></div>
                            <span class="absolute inset-y-0 right-3 hidden items-center text-xs font-semibold uppercase text-gray-500" data-card-brand></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="checkout-label" id="card-expiration-label">Validade</span>
                            <div class="checkout-input checkout-field" aria-labelledby="card-expiration-label"><div id="mp-expiration" class="h-full"></div></div>
                        </div>
                        <div>
                            <span class="checkout-label" id="card-cvv-label">Código de segurança</span>
                            <div class="checkout-input checkout-field" aria-labelledby="card-cvv-label"><div id="mp-security-code" class="h-full"></div></div>
                        </div>
                    </div>
                    <div>
                        <label for="cardholder" class="checkout-label">Nome impresso no cartão</label>
                        <input id="cardholder" type="text" autocomplete="cc-name" class="checkout-input" data-cardholder>
                    </div>
                    <div>
                        <label for="installments" class="checkout-label">Parcelas</label>
                        <select id="installments" name="installments" class="checkout-input" data-installments disabled>
                            <option value="1">Informe o número do cartão</option>
                        </select>
                    </div>
                </div>

                {{-- Pix --}}
                <div class="mt-5 hidden rounded-xl bg-gray-50 p-4 text-sm/6 text-gray-600 ring-1 ring-black/5" data-panel="pix">
                    Ao continuar, você recebe um QR Code e o código Pix copia e cola. Ele vale por {{ $pixMinutes }} minutos,
                    e o acesso é liberado assim que o pagamento é confirmado.
                </div>
            </fieldset>

            <p class="mt-6 hidden rounded-lg bg-red-50 px-4 py-3 text-sm/6 text-red-800 ring-1 ring-red-200" role="alert" data-checkout-error></p>

            <button type="submit" class="checkout-submit mt-7" data-checkout-submit
                    data-label-card="Pagar {{ $course->formattedPrice() }}"
                    data-label-pix="Gerar Pix de {{ $course->formattedPrice() }}">
                Pagar {{ $course->formattedPrice() }}
            </button>

            <p class="mt-5 flex items-start gap-2 text-[13px]/5 text-gray-500">
                <x-heroicon-s-lock-closed class="mt-0.5 size-4 flex-none text-gray-400"/>
                Pagamento processado pelo Mercado Pago. Os dados do cartão vão direto para o Mercado Pago e não passam pelo nosso servidor.
            </p>
        </form>
    </main>
</div>
</x-layouts.checkout>
