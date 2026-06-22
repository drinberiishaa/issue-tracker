@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; All projects</a>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold mb-2">{{ $project->name }}</h1>
            <p class="text-gray-600 mb-4">{{ $project->description ?: 'No description provided.' }}</p>
            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                @if ($project->owner)
                    <span>Owner: <strong class="text-gray-700">{{ $project->owner->name }}</strong></span>
                @endif
                @if ($project->start_date)
                    <span>Start: {{ $project->start_date->format('M j, Y') }}</span>
                @endif
                @if ($project->deadline)
                    <span>Deadline: {{ $project->deadline->format('M j, Y') }}</span>
                @endif
            </div>
        </div>

        @can('update', $project)
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('projects.edit', $project) }}"
                   class="text-sm border border-gray-300 px-3 py-1.5 rounded-md hover:bg-gray-50">Edit</a>
            </div>
        @endcan
    </div>
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold">Issues ({{ $project->issues->count() }})</h2>
    @auth
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}"
           class="bg-brand-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-brand-700">
            + New Issue
        </a>
    @endauth
</div>

@if ($project->issues->isEmpty())
    <div class="text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-200">
        No issues yet for this project.
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
        @foreach ($project->issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="flex items-center justify-between gap-4 p-4 hover:bg-gray-50">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $issue->title }}</p>
                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        @include('partials.status-badge', ['status' => $issue->status])
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                        @foreach ($issue->tags as $tag)
                            @include('partials.tag-pill', ['tag' => $tag])
                        @endforeach
                    </div>
                </div>
                <div class="text-xs text-gray-400 shrink-0 text-right">
                    <p>{{ $issue->comments_count }} comment{{ $issue->comments_count === 1 ? '' : 's' }}</p>
                    @if ($issue->due_date)
                        <p class="mt-1">Due {{ $issue->due_date->format('M j') }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
