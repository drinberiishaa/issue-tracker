<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    /**
     * Display a listing of issues with optional filters by status, priority, tag, and search term.
     * Eager-loads project and tags to avoid N+1 queries.
     */
    public function index(Request $request): View
    {
        $issues = Issue::query()
            ->with(['project', 'tags'])
            ->withCount('comments')
            ->status($request->string('status')->toString() ?: null)
            ->priority($request->string('priority')->toString() ?: null)
            ->tag($request->integer('tag') ?: null)
            ->search($request->string('q')->toString() ?: null)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tags = Tag::orderBy('name')->get();

        return view('issues.index', [
            'issues' => $issues,
            'tags' => $tags,
            'filters' => $request->only(['status', 'priority', 'tag', 'q']),
        ]);
    }

    /**
     * AJAX endpoint: same filtering logic as index(), returns a partial for live search/filtering.
     */
    public function search(Request $request): View
    {
        $issues = Issue::query()
            ->with(['project', 'tags'])
            ->withCount('comments')
            ->status($request->string('status')->toString() ?: null)
            ->priority($request->string('priority')->toString() ?: null)
            ->tag($request->integer('tag') ?: null)
            ->search($request->string('q')->toString() ?: null)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('issues.partials.list', compact('issues'));
    }

    /**
     * Show the form for creating a new issue. Optionally pre-selects a project.
     */
    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedProjectId = $request->integer('project_id') ?: null;

        return view('issues.create', compact('projects', 'tags', 'selectedProjectId'));
    }

    /**
     * Store a newly created issue and sync its tags.
     */
    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $issue = Issue::create($validated);
        $issue->tags()->sync($tagIds);

        return redirect()
            ->route('issues.show', $issue)
            ->with('status', 'Issue created successfully.');
    }

    /**
     * Display the given issue with tags, comments (paginated separately via AJAX), and members.
     */
    public function show(Issue $issue): View
    {
        $issue->load(['project', 'tags', 'members']);

        $allTags = Tag::orderBy('name')->get();
        $allUsers = \App\Models\User::orderBy('name')->get();

        return view('issues.show', compact('issue', 'allTags', 'allUsers'));
    }

    /**
     * Show the form for editing the given issue.
     */
    public function edit(Issue $issue): View
    {
        $issue->load('tags');
        $tags = Tag::orderBy('name')->get();

        return view('issues.edit', compact('issue', 'tags'));
    }

    /**
     * Update the given issue.
     */
    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());

        return redirect()
            ->route('issues.show', $issue)
            ->with('status', 'Issue updated successfully.');
    }

    /**
     * Delete the given issue (cascades to comments and tag/member links).
     */
    public function destroy(Issue $issue): RedirectResponse
    {
        $projectId = $issue->project_id;
        $issue->delete();

        return redirect()
            ->route('projects.show', $projectId)
            ->with('status', 'Issue deleted successfully.');
    }
}
