@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('issues.show', $issue) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-600 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to issue
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
            <h1 class="text-xl font-bold text-gray-900">Edit Issue</h1>
        </div>

        @include('partials.errors')

        <form method="POST" action="{{ route('issues.update', $issue) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $issue->title) }}" required
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">{{ old('description', $issue->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                        @foreach (\App\Models\Issue::STATUSES as $status)
                            <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority</label>
                    <select id="priority" name="priority" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                        @foreach (\App\Models\Issue::PRIORITIES as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', $issue->priority) === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Due date</label>
                    <input id="due_date" name="due_date" type="date"
                           value="{{ old('due_date', $issue->due_date?->format('Y-m-d')) }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                </div>
            </div>

            <p class="text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tags are managed from the issue detail page.
            </p>

            <div class="flex items-center justify-between pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
                <a href="{{ route('issues.show', $issue) }}" class="text-sm text-gray-400 hover:text-gray-600 font-medium">Cancel</a>
            </div>
        </form>

        <form method="POST" action="{{ route('issues.destroy', $issue) }}"
              onsubmit="return confirm('Delete this issue and all its comments? This cannot be undone.');"
              class="mt-8 pt-6 border-t border-gray-100">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete this issue
            </button>
        </form>
    </div>
</div>
@endsection
