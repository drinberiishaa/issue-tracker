@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-600 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to project
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
            <h1 class="text-xl font-bold text-gray-900">Edit Project</h1>
        </div>

        @include('partials.errors')

        <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('projects.partials.form', ['project' => $project])
            <div class="flex items-center justify-between pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
                <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-400 hover:text-gray-600 font-medium">Cancel</a>
            </div>
        </form>

        <form method="POST" action="{{ route('projects.destroy', $project) }}"
              onsubmit="return confirm('Delete this project and all its issues? This cannot be undone.');"
              class="mt-8 pt-6 border-t border-gray-100">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete this project
            </button>
        </form>
    </div>
</div>
@endsection
