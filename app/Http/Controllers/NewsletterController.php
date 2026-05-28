<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterSubscribeService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(private readonly NewsletterSubscribeService $subscribeService) {}

    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $data = array_merge(
            $request->only(['name', 'email', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term']),
            NewsletterSubscribeService::extractFromRequest($request),
        );

        $listSlug = $request->input('list', 'newsletter');

        $this->subscribeService->subscribe($data, $listSlug);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Inscrição realizada com sucesso!',
                'type' => 'success',
            ]);
        }

        return back()->with('success', 'Inscrição realizada com sucesso!');
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
