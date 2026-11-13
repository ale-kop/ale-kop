<?php

namespace App\Actions\Checkout;

use App\Models\CourseAccess;
use App\Models\Order;
use Illuminate\Support\Carbon;

/**
 * Copia para o pedido o estado de um pagamento do Mercado Pago
 * (resposta da criação, consulta ou webhook) e reage à mudança:
 * aprovado → libera o curso; estornado → suspende o acesso.
 */
class SyncOrderWithPayment
{
    public function __construct(private readonly FulfillOrder $fulfillOrder) {}

    /**
     * @param  array<string, mixed>  $payment
     */
    public function handle(Order $order, array $payment): Order
    {
        $mpStatus = (string) data_get($payment, 'status', 'pending');
        $previous = $order->status;
        $transaction = data_get($payment, 'point_of_interaction.transaction_data', []);

        $order->fill(array_filter([
            'mp_payment_id' => (string) data_get($payment, 'id') ?: null,
            'mp_status' => $mpStatus,
            'mp_status_detail' => data_get($payment, 'status_detail'),
            'pix_qr_code' => data_get($transaction, 'qr_code'),
            'pix_qr_code_base64' => data_get($transaction, 'qr_code_base64'),
            'pix_expires_at' => ($expires = data_get($payment, 'date_of_expiration')) ? Carbon::parse($expires) : null,
        ], fn ($value) => $value !== null));

        // Um pedido aprovado só sai desse estado por estorno.
        $status = Order::statusFromPayment($mpStatus);

        // Defesa extra: um pagamento com valor menor que o do pedido nunca libera o curso.
        if ($status === Order::STATUS_APPROVED && (float) data_get($payment, 'transaction_amount', 0) < (float) $order->amount) {
            $status = Order::STATUS_PENDING;
        }

        if ($previous !== Order::STATUS_APPROVED || $status === Order::STATUS_REFUNDED) {
            $order->status = $status;
        }

        $order->save();

        if ($order->isApproved() && $previous !== Order::STATUS_APPROVED) {
            $this->fulfillOrder->handle($order);
        }

        if ($order->status === Order::STATUS_REFUNDED && $previous === Order::STATUS_APPROVED && $order->user_id) {
            CourseAccess::query()
                ->where('user_id', $order->user_id)
                ->forCourse($order->course_id)
                ->where('source', FulfillOrder::SOURCE)
                ->update(['status' => 'refunded']);
        }

        return $order->refresh();
    }
}
