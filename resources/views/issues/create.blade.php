@extends('layouts.app')

@section('title', 'New Issue')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('issues.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-600 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to issues
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 text-brand-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </span>
            <h1 class="text-xl font-bold text-gray-900">New Issue</h1>
        </div>

        @include('partials.errors')

        <form method="POST" action="{{ route('issues.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1.5">Project</label>
                <select id="project_id" name="project_id" required
                        class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                    <option value="">Select a project...</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id', $selectedProjectId) == $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required placeholder="Brief summary of the issue"
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the issue in detail..."
                          class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                        @foreach (\App\Models\Issue::STATUSES as $status)
                            <option value="{{ $status }}" @selected(old('status', 'open') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority</label>
                    <select id="priority" name="priority" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                        @foreach (\App\Models\Issue::PRIORITIES as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Due date</label>
                    <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags as $tag)
                        <label class="inline-flex items-center gap-1.5 text-sm border border-gray-200 rounded-xl px-3.5 py-2 cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:bg-brand-50 has-[:checked]:border-brand-300 has-[:checked]:text-brand-700">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   @checked(in_array($tag->id, old('tags', [])))
                                   class="rounded border-gray-300 text-brand-600">
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Issue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
