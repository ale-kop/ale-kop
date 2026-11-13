<?php

namespace App\Services\MercadoPago;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cliente mínimo da API de pagamentos do Mercado Pago (Checkout Transparente).
 * Os dados do cartão nunca passam pelo servidor: o navegador gera um token
 * com o MercadoPago.js (Secure Fields) e só o token chega aqui.
 */
class MercadoPagoClient
{
    private const BASE_URL = 'https://api.mercadopago.com';

    /**
     * Cria um pagamento. A chave de idempotência evita cobrança em dobro
     * se a mesma requisição for reenviada.
     *
     * @return array<string, mixed>
     */
    public function createPayment(array $payload, string $idempotencyKey): array
    {
        try {
            return $this->http()
                ->withHeaders(['X-Idempotency-Key' => $idempotencyKey])
                ->post('/v1/payments', $payload)
                ->throw()
                ->json();
        } catch (RequestException $e) {
            Log::warning('Mercado Pago recusou a criação do pagamento', [
                'status' => $e->response->status(),
                'body' => $e->response->json(),
            ]);

            throw new PaymentException(
                'Não foi possível processar o pagamento. Confira os dados e tente de novo.',
                previous: $e,
            );
        } catch (ConnectionException $e) {
            Log::error('Mercado Pago indisponível', ['error' => $e->getMessage()]);

            throw new PaymentException(
                'O serviço de pagamento não respondeu. Tente de novo em instantes.',
                previous: $e,
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayment(string $paymentId): array
    {
        return $this->http()
            ->get('/v1/payments/'.rawurlencode($paymentId))
            ->throw()
            ->json();
    }

    /**
     * Valida o cabeçalho x-signature das notificações (webhooks).
     * Manifesto: "id:{data.id};request-id:{x-request-id};ts:{ts};", HMAC-SHA256 com a chave secreta.
     */
    public function hasValidSignature(Request $request, string $dataId): bool
    {
        $secret = config('services.mercadopago.webhook_secret');
        $header = (string) $request->header('x-signature');

        if (! $secret || $header === '') {
            return false;
        }

        $parts = collect(explode(',', $header))
            ->mapWithKeys(function (string $part) {
                [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');

                return [$key => $value];
            });

        $ts = $parts->get('ts');
        $hash = $parts->get('v1');

        if (! $ts || ! $hash) {
            return false;
        }

        $manifest = 'id:'.strtolower($dataId).';';
        if ($requestId = $request->header('x-request-id')) {
            $manifest .= 'request-id:'.$requestId.';';
        }
        $manifest .= 'ts:'.$ts.';';

        return hash_equals(hash_hmac('sha256', $manifest, $secret), $hash);
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL)
            ->withToken((string) config('services.mercadopago.access_token'))
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->connectTimeout(5);
    }
}
