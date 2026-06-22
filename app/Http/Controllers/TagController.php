<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * List all tags, with the number of issues using each (avoids N+1 on the view).
     */
    public function index(): View
    {
        $tags = Tag::withCount('issues')->orderBy('name')->get();

        return view('tags.index', compact('tags'));
    }

    /**
     * Create a new tag. Supports both a normal form submit and an AJAX (JSON) request,
     * since the issue detail page lets users create a tag on the fly from the attach modal.
     */
    public function store(StoreTagRequest $request): RedirectResponse|JsonResponse
    {
        $tag = Tag::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json(['tag' => $tag], 201);
        }

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag created successfully.');
    }
}
