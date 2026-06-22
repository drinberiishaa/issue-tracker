@extends('layouts.app')

@section('title', 'New Issue')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h1 class="text-2xl font-bold mb-6">New Issue</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('issues.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Project</label>
            <select id="project_id" name="project_id" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <option value="">Select a project…</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected(old('project_id', $selectedProjectId) == $project->id)>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (\App\Models\Issue::STATUSES as $status)
                        <option value="{{ $status }}" @selected(old('status', 'open') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select id="priority" name="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (\App\Models\Issue::PRIORITIES as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due date</label>
                <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
            <div class="flex flex-wrap gap-2">
                @foreach ($tags as $tag)
                    <label class="inline-flex items-center gap-1.5 text-sm border border-gray-300 rounded-full px-3 py-1 cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               @checked(in_array($tag->id, old('tags', [])))
                               class="rounded border-gray-300 text-brand-600">
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-md font-medium hover:bg-brand-700">
            Create Issue
        </button>
    </form>
</div>
@endsection
