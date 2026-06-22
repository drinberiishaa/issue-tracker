<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachMemberRequest;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class IssueMemberController extends Controller
{
    /**
     * Assign a member (user) to an issue via AJAX. Returns the updated member list as JSON.
     */
    public function store(AttachMemberRequest $request, Issue $issue): JsonResponse
    {
        $issue->members()->syncWithoutDetaching([$request->validated('user_id')]);

        return response()->json([
            'members' => $issue->members()->orderBy('name')->get(['users.id', 'users.name', 'users.email']),
        ]);
    }

    /**
     * Remove a member from an issue via AJAX. Returns the updated member list as JSON.
     */
    public function destroy(Issue $issue, User $user): JsonResponse
    {
        $issue->members()->detach($user->id);

        return response()->json([
            'members' => $issue->members()->orderBy('name')->get(['users.id', 'users.name', 'users.email']),
        ]);
    }
}
