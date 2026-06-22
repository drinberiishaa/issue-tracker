<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects, with issue counts eager-loaded to avoid N+1.
     */
    public function index(): View
    {
        $projects = Project::query()
            ->withCount(['issues', 'issues as open_issues_count' => function ($query) {
                $query->where('status', '!=', 'closed');
            }])
            ->with('owner')
            ->latest()
            ->paginate(9);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project, owned by the current user.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create([
            ...$request->validated(),
            'owner_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Project created successfully.');
    }

    /**
     * Display the given project along with its issues (eager loaded to avoid N+1).
     */
    public function show(Project $project): View
    {
        $project->load([
            'owner',
            'issues' => function ($query) {
                $query->withCount('comments')->with('tags')->latest();
            },
        ]);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the given project.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the given project.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Project updated successfully.');
    }

    /**
     * Delete the given project (cascades to issues, comments, tag links).
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project deleted successfully.');
    }
}
