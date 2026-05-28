<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterListRequest;
use App\Models\NewsletterList;
use Illuminate\Support\Str;

class NewsletterListController extends Controller
{
    public function index()
    {
        $lists = NewsletterList::withCount('subscribers')->latest()->paginate(20);

        return view('admin.newsletter.lists.index', compact('lists'));
    }

    public function create()
    {
        return view('admin.newsletter.lists.create');
    }

    public function store(NewsletterListRequest $request)
    {
        NewsletterList::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Lista criada com sucesso.', 'type' => 'success']);
        }

        return redirect()->route('admin.newsletter.lists.index')->with('success', 'Lista criada com sucesso.');
    }

    public function edit(NewsletterList $list)
    {
        $list->loadCount('subscribers');

        return view('admin.newsletter.lists.edit', compact('list'));
    }

    public function update(NewsletterListRequest $request, NewsletterList $list)
    {
        $list->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Lista atualizada com sucesso.', 'type' => 'success']);
        }

        return redirect()->route('admin.newsletter.lists.index')->with('success', 'Lista atualizada com sucesso.');
    }

    public function destroy(NewsletterList $list)
    {
        $list->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Lista removida.', 'type' => 'success']);
        }

        return redirect()->route('admin.newsletter.lists.index')->with('success', 'Lista removida.');
    }
}
