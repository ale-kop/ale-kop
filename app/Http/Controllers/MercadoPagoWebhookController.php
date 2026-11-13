<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\SyncOrderWithPayment;
use App\Models\Order;
use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Notificações de pagamento do Mercado Pago (webhook).
 * O corpo da notificação só traz o id: o estado real é sempre consultado na API.
 */
class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $request, MercadoPagoClient $mercadoPago, SyncOrderWithPayment $syncOrder): Response
    {
        $type = $request->query('type') ?? $request->input('type');
        // "data.id" na query string vira "data_id" no PHP.
        $paymentId = (string) ($request->query('data_id') ?? $request->input('data.id'));

        if ($type !== 'payment' || $paymentId === '') {
            return response()->noContent();
        }

        if (! $mercadoPago->hasValidSignature($request, $paymentId)) {
            Log::warning('Webhook do Mercado Pago com assinatura inválida', ['payment' => $paymentId]);

            return response('Assinatura inválida', 401);
        }

        try {
            $payment = $mercadoPago->getPayment($paymentId);
        } catch (Throwable $e) {
            Log::error('Webhook do Mercado Pago: falha ao consultar pagamento', [
                'payment' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            // 5xx faz o Mercado Pago reenviar a notificação mais tarde.
            return response('Falha ao consultar pagamento', 503);
        }

        $order = Order::query()->where('uuid', data_get($payment, 'external_reference'))->first();

        if ($order) {
            $syncOrder->handle($order, $payment);
        }

        return response()->noContent();
    }
}
