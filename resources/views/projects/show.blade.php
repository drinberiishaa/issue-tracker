@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-600 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        All projects
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 mb-8 shadow-soft">
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div class="flex items-start gap-3 sm:gap-4">
            <span class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-brand-50 text-brand-600 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </span>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1.5">{{ $project->name }}</h1>
                <p class="text-gray-500 mb-4">{{ $project->description ?: 'No description provided.' }}</p>
                <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                    @if ($project->owner)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <strong class="text-gray-700 font-medium">{{ $project->owner->name }}</strong>
                        </span>
                    @endif
                    @if ($project->start_date)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Start: {{ $project->start_date->format('M j, Y') }}
                        </span>
                    @endif
                    @if ($project->deadline)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Deadline: {{ $project->deadline->format('M j, Y') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}" class="btn-secondary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
        @endcan
    </div>
</div>

<div class="flex items-start sm:items-center justify-between gap-3 mb-5">
    <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Issues
        <span class="text-sm font-normal text-gray-400">({{ $project->issues->count() }})</span>
    </h2>
    @auth
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Issue
        </a>
    @endauth
</div>

@if ($project->issues->isEmpty())
    <div class="empty-state text-center py-16 bg-white rounded-2xl border border-gray-200 shadow-soft">
        <div class="flex justify-center mb-3">
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </span>
        </div>
        <p class="text-gray-500 font-medium">No issues yet for this project</p>
        <p class="text-sm text-gray-400 mt-1">Create your first issue to get started</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100 shadow-soft overflow-hidden">
        @foreach ($project->issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="issue-row flex items-center justify-between gap-4 p-4 sm:px-6">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $issue->title }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @include('partials.status-badge', ['status' => $issue->status])
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                        @foreach ($issue->tags as $tag)
                            @include('partials.tag-pill', ['tag' => $tag])
                        @endforeach
                    </div>
                </div>
                <div class="text-xs text-gray-400 shrink-0 text-right space-y-1">
                    <p class="flex items-center gap-1 justify-end">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $issue->comments_count }}
                    </p>
                    @if ($issue->due_date)
                        <p>Due {{ $issue->due_date->format('M j') }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
