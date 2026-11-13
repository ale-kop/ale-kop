<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Support\Str;

/**
 * Envia o pagamento de um pedido ao Mercado Pago (cartão ou Pix)
 * e sincroniza o pedido com a resposta.
 */
class PayOrder
{
    public function __construct(
        private readonly MercadoPagoClient $mercadoPago,
        private readonly SyncOrderWithPayment $syncOrder,
    ) {}

    /**
     * @param  array{token?: string, payment_method_id?: string, issuer_id?: string|null, installments?: int}  $card
     */
    public function handle(Order $order, array $card = []): Order
    {
        $payload = $order->isPix()
            ? $this->pixPayload($order)
            : $this->cardPayload($order, $card);

        $payment = $this->mercadoPago->createPayment($payload, $order->uuid);

        return $this->syncOrder->handle($order, $payment);
    }

    private function basePayload(Order $order): array
    {
        $course = $order->course;

        $payload = [
            'transaction_amount' => (float) $order->amount,
            'description' => $course->name,
            'external_reference' => $order->uuid,
            'statement_descriptor' => config('services.mercadopago.statement_descriptor'),
            'payer' => [
                'email' => $order->email,
                'first_name' => $order->firstName(),
                'last_name' => Str::of($order->name)->after(' ')->trim()->toString() ?: null,
                'identification' => ['type' => 'CPF', 'number' => $order->document],
            ],
            'additional_info' => [
                'items' => [[
                    'id' => $course->slug,
                    'title' => $course->name,
                    'category_id' => 'learnings',
                    'quantity' => 1,
                    'unit_price' => (float) $order->amount,
                ]],
            ],
        ];

        // O Mercado Pago só aceita notification_url pública; em ambiente local o status vem por consulta.
        if (! app()->environment('local', 'testing')) {
            $payload['notification_url'] = route('webhooks.mercadopago');
        }

        return $payload;
    }

    private function cardPayload(Order $order, array $card): array
    {
        return array_filter([
            ...$this->basePayload($order),
            'token' => $card['token'],
            'payment_method_id' => $card['payment_method_id'],
            'issuer_id' => $card['issuer_id'] ?? null,
            'installments' => $order->installments,
        ], fn ($value) => $value !== null);
    }

    private function pixPayload(Order $order): array
    {
        $minutes = config('services.mercadopago.pix_expiration_minutes');

        return [
            ...$this->basePayload($order),
            'payment_method_id' => 'pix',
            'date_of_expiration' => now()->addMinutes($minutes)->format('Y-m-d\TH:i:s.vP'),
        ];
    }
}
