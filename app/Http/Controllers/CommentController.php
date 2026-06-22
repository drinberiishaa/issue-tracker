<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Return a paginated page of comments for an issue, newest first, as JSON (used for AJAX loading).
     */
    public function index(Request $request, Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->orderByDesc('created_at')
            ->paginate(5, ['id', 'author_name', 'body', 'created_at'], 'page', $request->integer('page', 1));

        return response()->json($comments);
    }

    /**
     * Store a new comment on an issue via AJAX. Returns the created comment as JSON.
     */
    public function store(StoreCommentRequest $request, Issue $issue): JsonResponse
    {
        $comment = $issue->comments()->create($request->validated());

        return response()->json(['comment' => $comment], 201);
    }
}
