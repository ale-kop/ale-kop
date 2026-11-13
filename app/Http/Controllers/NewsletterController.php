<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Http\Requests\NewsletterVerifyRequest;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterSubscribeService;
use App\Services\NewsletterVerificationService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(private readonly NewsletterSubscribeService $subscribeService) {}

    /** Etapa 1: registra o e-mail como pendente e envia o código de confirmação. */
    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $data = array_merge(
            $request->only(['name', 'email', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term']),
            NewsletterSubscribeService::extractFromRequest($request),
        );

        $this->subscribeService->subscribe($data);

        $message = "Enviamos um código de 6 dígitos para {$data['email']}.";

        if ($request->wantsJson()) {
            return response()->json([
                'type' => 'success',
                'step' => 'verify',
                'message' => $message,
                'email' => $data['email'],
                'expires_in_minutes' => NewsletterVerificationService::CODE_TTL_MINUTES,
                'retry_after' => NewsletterVerificationService::RESEND_COOLDOWN_SECONDS,
            ]);
        }

        return back()->with('success', $message);
    }

    /** Etapa 2: confere o código e confirma a inscrição. */
    public function verify(NewsletterVerifyRequest $request)
    {
        $confirmed = $this->subscribeService->verify(
            $request->validated('email'),
            $request->validated('code'),
            $request->input('list', 'newsletter'),
        );

        if (! $confirmed) {
            $message = 'Código inválido ou expirado. Confira os 6 dígitos ou peça um novo código.';

            return $request->wantsJson()
                ? response()->json(['type' => 'warning', 'message' => $message], 422)
                : back()->withErrors(['code' => $message]);
        }

        $message = 'Inscrição confirmada!';

        return $request->wantsJson()
            ? response()->json(['type' => 'success', 'step' => 'done', 'message' => $message])
            : back()->with('success', $message);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        return view('newsletter.unsubscribe', compact('subscriber', 'token'));
    }

    public function confirmUnsubscribe(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        if ($subscriber->status !== 'unsubscribed') {
            $subscriber->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ]);
        }

        return view('newsletter.unsubscribed', compact('subscriber'));
    }
}
