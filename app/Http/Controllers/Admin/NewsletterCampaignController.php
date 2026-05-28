<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterCampaignRequest;
use App\Jobs\ProcessNewsletterCampaign;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $campaigns = NewsletterCampaign::with('createdBy')
            ->latest()
            ->paginate(20);

        return view('admin.newsletter.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $lists = NewsletterList::withCount('subscribers')->get();

        return view('admin.newsletter.campaigns.create', compact('lists'));
    }

    public function store(NewsletterCampaignRequest $request)
    {
        $campaign = NewsletterCampaign::create([
            'title' => $request->title,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => $request->scheduled_at ? 'scheduled' : 'draft',
            'scheduled_at' => $request->scheduled_at,
            'created_by' => Auth::id(),
        ]);

        $campaign->lists()->sync($request->list_ids);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Campanha criada.', 'type' => 'success', 'redirect' => route('admin.newsletter.campaigns.index')]);
        }

        return redirect()->route('admin.newsletter.campaigns.index')->with('success', 'Campanha criada com sucesso.');
    }

    public function edit(NewsletterCampaign $campaign)
    {
        abort_unless($campaign->isDraft() || $campaign->isScheduled(), 403, 'Campanha não pode ser editada.');

        $lists = NewsletterList::withCount('subscribers')->get();
        $campaign->load('lists');

        return view('admin.newsletter.campaigns.edit', compact('campaign', 'lists'));
    }

    public function update(NewsletterCampaignRequest $request, NewsletterCampaign $campaign)
    {
        abort_unless($campaign->isDraft() || $campaign->isScheduled(), 403, 'Campanha não pode ser editada.');

        $campaign->update([
            'title' => $request->title,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => $request->scheduled_at ? 'scheduled' : 'draft',
            'scheduled_at' => $request->scheduled_at,
        ]);

        $campaign->lists()->sync($request->list_ids);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Campanha atualizada.', 'type' => 'success']);
        }

        return redirect()->route('admin.newsletter.campaigns.index')->with('success', 'Campanha atualizada.');
    }

    public function show(NewsletterCampaign $campaign)
    {
        $campaign->load('lists');

        return view('admin.newsletter.campaigns.show', compact('campaign'));
    }

    public function send(NewsletterCampaign $campaign)
    {
        abort_unless($campaign->isDraft() || $campaign->isScheduled(), 422, 'Esta campanha não pode ser enviada.');

        $campaign->update(['status' => 'scheduled']);

        ProcessNewsletterCampaign::dispatch($campaign)->onQueue('newsletter');

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Campanha enfileirada para envio.',
                'type' => 'success',
                'redirect' => route('admin.newsletter.campaigns.show', $campaign),
            ]);
        }

        return redirect()->route('admin.newsletter.campaigns.show', $campaign)
            ->with('success', 'Campanha enfileirada para envio.');
    }

    public function cancel(NewsletterCampaign $campaign)
    {
        abort_unless(in_array($campaign->status, ['scheduled', 'draft']), 422, 'Campanha não pode ser cancelada.');

        $campaign->update(['status' => 'cancelled']);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Campanha cancelada.', 'type' => 'success']);
        }

        return redirect()->route('admin.newsletter.campaigns.index')->with('success', 'Campanha cancelada.');
    }

    public function destroy(NewsletterCampaign $campaign)
    {
        abort_unless($campaign->isDraft() || $campaign->status === 'cancelled', 422, 'Somente campanhas em rascunho ou canceladas podem ser removidas.');

        $campaign->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Campanha removida.', 'type' => 'success']);
        }

        return redirect()->route('admin.newsletter.campaigns.index')->with('success', 'Campanha removida.');
    }
}
