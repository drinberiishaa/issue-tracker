@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h1 class="text-2xl font-bold mb-6">Edit Issue</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('issues.update', $issue) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $issue->title) }}" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('description', $issue->description) }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (\App\Models\Issue::STATUSES as $status)
                        <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select id="priority" name="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (\App\Models\Issue::PRIORITIES as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', $issue->priority) === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due date</label>
                <input id="due_date" name="due_date" type="date"
                       value="{{ old('due_date', $issue->due_date?->format('Y-m-d')) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
        </div>

        <p class="text-xs text-gray-400">Tags are managed from the issue detail page.</p>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-md font-medium hover:bg-brand-700">
                Save Changes
            </button>
            <a href="{{ route('issues.show', $issue) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>

    <form method="POST" action="{{ route('issues.destroy', $issue) }}"
          onsubmit="return confirm('Delete this issue and all its comments? This cannot be undone.');"
          class="mt-6 pt-6 border-t border-gray-100">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">
            Delete this issue
        </button>
    </form>
</div>
@endsection
