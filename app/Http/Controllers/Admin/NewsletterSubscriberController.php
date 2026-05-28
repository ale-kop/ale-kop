<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::with('lists')
            ->latest('subscribed_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('list')) {
            $query->whereHas('lists', fn ($q) => $q->where('newsletter_lists.id', $request->list));
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q
                ->where('email', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%")
            );
        }

        $subscribers = $query->paginate(50)->withQueryString();
        $lists = NewsletterList::orderBy('name')->get(['id', 'name']);

        $counts = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('status', 'active')->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
            'bounced' => NewsletterSubscriber::where('status', 'bounced')->count(),
        ];

        return view('admin.newsletter.subscribers.index', compact('subscribers', 'lists', 'counts'));
    }
}
