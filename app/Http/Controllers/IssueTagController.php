<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTagRequest;
use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class IssueTagController extends Controller
{
    /**
     * Attach a tag to an issue via AJAX. Returns the updated tag list as JSON.
     */
    public function store(AttachTagRequest $request, Issue $issue): JsonResponse
    {
        $issue->tags()->syncWithoutDetaching([$request->validated('tag_id')]);

        return response()->json([
            'tags' => $issue->tags()->orderBy('name')->get(),
        ]);
    }

    /**
     * Detach a tag from an issue via AJAX. Returns the updated tag list as JSON.
     */
    public function destroy(Issue $issue, Tag $tag): JsonResponse
    {
        $issue->tags()->detach($tag->id);

        return response()->json([
            'tags' => $issue->tags()->orderBy('name')->get(),
        ]);
    }
}
