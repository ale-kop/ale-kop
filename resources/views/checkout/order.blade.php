{{--
    Página do pedido (URL com o uuid): aprovado, Pix aguardando pagamento,
    cartão em análise ou pagamento não concluído. O Pix pendente consulta o status
    sozinho (resources/js/modules/checkout.js → [data-pix]).
--}}
@php
    $pixOpen = $order->isPix() && $order->isPending() && $order->pix_qr_code
        && (! $order->pix_expires_at || $order->pix_expires_at->isFuture());
@endphp

<x-layouts.checkout title="Seu pedido">
<main class="mx-auto max-w-lg px-5 py-12 sm:py-20">
    <a href="{{ route('index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-950">{{ config('app.name') }}</a>

    @if($order->isApproved())
        <div class="mt-10">
            <span class="grid size-12 place-items-center rounded-full bg-done-light text-done"><x-heroicon-s-check class="size-6"/></span>
            <h1 class="mt-6 text-3xl font-bold tracking-[-0.03em]">Pagamento aprovado</h1>
            <p class="mt-4 font-serif text-lg/8 text-gray-600">
                Obrigado, {{ $order->firstName() }}. Seu acesso a <strong class="font-semibold text-gray-900">{{ $order->course->name }}</strong>
                está liberado. Enviamos para <strong class="font-semibold text-gray-900">{{ $order->email }}</strong> o link para entrar.
            </p>
            @auth
                <a href="{{ route('courses.show', $order->course->slug) }}" class="checkout-submit mt-8 inline-flex w-auto px-7">Abrir o curso</a>
            @else
                <p class="mt-4 font-serif text-lg/8 text-gray-600">Se você ainda não tem senha, o e-mail traz o link para criar uma.</p>
                <a href="{{ route('login') }}" class="checkout-submit mt-8 inline-flex w-auto px-7">Entrar</a>
            @endauth
        </div>

    @elseif($pixOpen)
        <div class="mt-10" data-pix
             data-status-url="{{ route('checkout.status', $order) }}"
             data-expires-at="{{ $order->pix_expires_at?->toIso8601String() }}">
            <h1 class="text-3xl font-bold tracking-[-0.03em]">Pague com Pix</h1>
            <p class="mt-3 font-serif text-lg/8 text-gray-600">
                Abra o app do seu banco, escolha pagar com Pix e escaneie o QR Code ou cole o código.
                Valor: <strong class="font-semibold text-gray-900">{{ $order->formattedAmount() }}</strong>.
            </p>

            <div class="mt-8 flex flex-col items-center rounded-2xl bg-gray-50 p-6 ring-1 ring-black/5">
                @if($order->pix_qr_code_base64)
                    <img src="data:image/png;base64,{{ $order->pix_qr_code_base64 }}" alt="QR Code Pix de {{ $order->formattedAmount() }}"
                         class="size-56 rounded-lg bg-white p-2 ring-1 ring-black/5">
                @endif

                <label for="pix-code" class="mt-6 self-start text-sm font-medium text-gray-700">Pix copia e cola</label>
                <div class="mt-1.5 flex w-full gap-2">
                    <input id="pix-code" type="text" readonly value="{{ $order->pix_qr_code }}" class="checkout-input min-w-0 flex-1 font-mono text-xs" data-pix-code>
                    <button type="button" class="checkout-secondary" data-pix-copy>Copiar</button>
                </div>
            </div>

            <p class="mt-6 flex items-center gap-2.5 text-sm text-gray-600" aria-live="polite">
                <span class="relative flex size-2.5"><span class="absolute inline-flex size-full animate-ping rounded-full bg-brand opacity-60"></span><span class="relative inline-flex size-2.5 rounded-full bg-brand"></span></span>
                <span data-pix-status>Aguardando o pagamento. Esta página atualiza sozinha.</span>
            </p>
            @if($order->pix_expires_at)
                <p class="mt-2 text-sm text-gray-500">O código expira em <span class="tabular-nums" data-pix-countdown>{{ $order->pix_expires_at->diffForHumans(syntax: \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}</span>.</p>
            @endif
        </div>

    @elseif($order->isPending() && ! $order->isPix())
        <div class="mt-10">
            <span class="grid size-12 place-items-center rounded-full bg-brand-light text-brand-deep"><x-heroicon-o-clock class="size-6"/></span>
            <h1 class="mt-6 text-3xl font-bold tracking-[-0.03em]">Pagamento em análise</h1>
            <p class="mt-4 font-serif text-lg/8 text-gray-600">
                O Mercado Pago está analisando o pagamento com cartão. Isso costuma levar poucos minutos, mas pode levar até 2 dias úteis.
                Quando for aprovado, você recebe o acesso em <strong class="font-semibold text-gray-900">{{ $order->email }}</strong>.
            </p>
        </div>

    @elseif($order->status === \App\Models\Order::STATUS_REFUNDED)
        <div class="mt-10">
            <h1 class="text-3xl font-bold tracking-[-0.03em]">Pedido reembolsado</h1>
            <p class="mt-4 font-serif text-lg/8 text-gray-600">O valor deste pedido foi devolvido e o acesso ao curso foi encerrado.</p>
        </div>

    @else
        <div class="mt-10">
            <span class="grid size-12 place-items-center rounded-full bg-accent-light text-accent"><x-heroicon-o-x-mark class="size-6"/></span>
            <h1 class="mt-6 text-3xl font-bold tracking-[-0.03em]">Pagamento não concluído</h1>
            <p class="mt-4 font-serif text-lg/8 text-gray-600">
                @if($order->isPix())
                    O código Pix expirou antes do pagamento. Gere um novo para continuar.
                @else
                    O pagamento não foi aprovado e nada foi cobrado. Tente outro cartão ou pague com Pix.
                @endif
            </p>
            <a href="{{ route('checkout.show', $order->course->slug) }}" class="checkout-submit mt-8 inline-flex w-auto px-7">Tentar de novo</a>
        </div>
    @endif

    <p class="mt-14 text-xs text-gray-400">Pedido {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($order->uuid, 0, 8)) }}</p>
</main>
</x-layouts.checkout>
