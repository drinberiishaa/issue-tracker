@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="flex items-start sm:items-center justify-between gap-3 mb-6 sm:mb-8">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Projects</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage and track all your projects</p>
    </div>
    @auth
        <a href="{{ route('projects.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Project
        </a>
    @endauth
</div>

@if ($projects->isEmpty())
    <div class="empty-state text-center py-20 bg-white rounded-2xl border border-gray-200 shadow-soft">
        <div class="flex justify-center mb-4">
            <span class="flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-50 text-brand-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </span>
        </div>
        <p class="text-gray-500 font-medium">No projects yet</p>
        <p class="text-sm text-gray-400 mt-1">Create your first project to start tracking issues</p>
        @auth
            <a href="{{ route('projects.create') }}" class="btn-primary mt-5 inline-flex">Create the first one</a>
        @endauth
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @foreach ($projects as $project)
            <a href="{{ route('projects.show', $project) }}"
               class="card-hover block bg-white rounded-2xl border border-gray-200 p-6 shadow-soft">
                <div class="flex items-start justify-between mb-3">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 text-brand-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    </span>
                    <span class="text-xs font-medium text-gray-400">{{ $project->issues_count }} issue{{ $project->issues_count === 1 ? '' : 's' }}</span>
                </div>
                <h2 class="font-semibold text-gray-900 text-lg mb-1.5 truncate">{{ $project->name }}</h2>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4 min-h-[2.5rem]">{{ $project->description ?: 'No description.' }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $project->open_issues_count > 0 ? 'bg-blue-500' : 'bg-emerald-500' }}"></span>
                        <span class="text-xs font-medium {{ $project->open_issues_count > 0 ? 'text-blue-600' : 'text-emerald-600' }}">{{ $project->open_issues_count }} open</span>
                    </div>
                    @if ($project->owner)
                        <div class="flex items-center gap-1.5 text-xs text-gray-400">
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-500 text-[10px] font-bold">{{ strtoupper(substr($project->owner->name, 0, 1)) }}</span>
                            {{ $project->owner->name }}
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $projects->links() }}
    </div>
@endif
@endsection
