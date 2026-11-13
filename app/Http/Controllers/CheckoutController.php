<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\PayOrder;
use App\Actions\Checkout\SyncOrderWithPayment;
use App\Http\Requests\CheckoutRequest;
use App\Models\Course;
use App\Models\Order;
use App\Services\MercadoPago\MercadoPagoClient;
use App\Services\MercadoPago\PaymentException;
use App\Services\MercadoPago\StatusDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function show(Request $request, string $courseSlug): View|RedirectResponse
    {
        $course = $this->sellableCourse($courseSlug);
        $user = $request->user();

        if ($user?->canAccessCourse($course)) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('status', 'Você já tem acesso a este curso.');
        }

        return view('checkout.show', [
            'course' => $course,
            'user' => $user,
            // "Voltar" leva à página de vendas quando o curso tem uma; senão, à página do curso.
            'backUrl' => $course->slug === config('offers.whatsapp.course_slug')
                ? route('offers.whatsapp')
                : route('courses.show', $course->slug),
            'image' => $course->image('large') ?: asset('img/escrever-orcamento-de-servicos-akop.webp'),
            'publicKey' => config('services.mercadopago.public_key'),
            'maxInstallments' => config('services.mercadopago.max_installments'),
            'pixMinutes' => config('services.mercadopago.pix_expiration_minutes'),
        ]);
    }

    public function pay(CheckoutRequest $request, string $courseSlug, PayOrder $payOrder): JsonResponse
    {
        $course = $this->sellableCourse($courseSlug);
        $data = $request->validated();
        $isPix = $data['payment_method'] === 'pix';

        $order = Order::query()->create([
            'user_id' => $request->user()?->id,
            'course_id' => $course->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'document' => $data['document'],
            'amount' => $course->price(),
            'payment_method' => $data['payment_method'],
            'installments' => $isPix ? 1 : (int) $data['installments'],
        ]);

        try {
            $order = $payOrder->handle($order, $data);
        } catch (PaymentException $e) {
            $order->update(['status' => Order::STATUS_CANCELLED]);

            return response()->json(['message' => $e->getMessage()], 422);
        }

        if ($order->status === Order::STATUS_REJECTED) {
            return response()->json([
                'status' => $order->status,
                'message' => StatusDetail::message($order->mp_status_detail),
            ], 422);
        }

        // Aprovado, em análise ou Pix aguardando: a página do pedido mostra cada caso (inclusive o QR Code).
        return response()->json([
            'status' => $order->status,
            'redirect' => route('checkout.order', $order),
        ]);
    }

    /**
     * Página do pedido: confirmação, Pix aguardando pagamento ou recusa.
     */
    public function order(Order $order): View
    {
        return view('checkout.order', ['order' => $order->load('course')]);
    }

    /**
     * Consulta usada pelo checkout enquanto o Pix não é pago. O webhook é a fonte
     * principal; aqui o pedido é reconsultado no Mercado Pago no máximo a cada 5s,
     * o que também cobre o ambiente local, onde o webhook não chega.
     */
    public function status(Order $order, MercadoPagoClient $mercadoPago, SyncOrderWithPayment $syncOrder): JsonResponse
    {
        if ($order->isPending() && $order->mp_payment_id && $order->updated_at->lt(now()->subSeconds(5))) {
            try {
                $order = $syncOrder->handle($order, $mercadoPago->getPayment($order->mp_payment_id));
            } catch (Throwable $e) {
                Log::warning('Falha ao consultar pagamento no Mercado Pago', [
                    'order' => $order->uuid,
                    'error' => $e->getMessage(),
                ]);
                $order->touch();
            }
        }

        return response()->json([
            'status' => $order->status,
            'redirect' => route('checkout.order', $order),
        ]);
    }

    private function sellableCourse(string $slug): Course
    {
        $course = Course::query()->where('slug', $slug)->firstOrFail();

        abort_unless($course->requiresAccess() && $course->hasPrice(), 404);

        return $course;
    }
}
