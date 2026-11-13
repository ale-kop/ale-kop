<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Novo código pedido antes do intervalo mínimo entre envios. */
class NewsletterCodeCooldownException extends Exception
{
    public function __construct(public readonly int $retryAfter)
    {
        parent::__construct("Você acabou de pedir um código. Aguarde {$retryAfter}s para pedir outro.");
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'type' => 'warning',
                'retry_after' => $this->retryAfter,
            ], 429);
        }

        return back()->withErrors(['email' => $this->getMessage()]);
    }
}
