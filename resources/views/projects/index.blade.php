@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Projects</h1>
    @auth
        <a href="{{ route('projects.create') }}" class="bg-brand-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-brand-700">
            + New Project
        </a>
    @endauth
</div>

@if ($projects->isEmpty())
    <div class="text-center py-16 text-gray-500 bg-white rounded-xl border border-gray-200">
        <p>No projects yet.</p>
        @auth
            <a href="{{ route('projects.create') }}" class="text-brand-600 font-medium">Create the first one</a>
        @endauth
    </div>
@else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($projects as $project)
            <a href="{{ route('projects.show', $project) }}"
               class="block bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-300 hover:shadow-sm transition">
                <h2 class="font-semibold text-lg mb-1 truncate">{{ $project->name }}</h2>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $project->description ?: 'No description.' }}</p>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>{{ $project->issues_count }} issue{{ $project->issues_count === 1 ? '' : 's' }}</span>
                    <span>{{ $project->open_issues_count }} open</span>
                </div>
                @if ($project->owner)
                    <div class="mt-3 text-xs text-gray-400">Owned by {{ $project->owner->name }}</div>
                @endif
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $projects->links() }}
    </div>
@endif
@endsection
