@extends('layouts.app')

@section('title', 'Issues')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Issues</h1>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <div class="grid sm:grid-cols-4 gap-3">
        <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search title / description</label>
            <input type="text" id="issue-search" value="{{ $filters['q'] ?? '' }}" placeholder="Type to search…"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select id="filter-status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                <option value="">All</option>
                @foreach (\App\Models\Issue::STATUSES as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Priority</label>
            <select id="filter-priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                <option value="">All</option>
                @foreach (\App\Models\Issue::PRIORITIES as $priority)
                    <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-3">
        <label class="block text-xs font-medium text-gray-500 mb-1">Tag</label>
        <div class="flex flex-wrap gap-2" id="filter-tags">
            <button type="button" data-tag-id="" class="tag-filter-btn badge border {{ empty($filters['tag']) ? 'bg-brand-600 text-white border-brand-600' : 'border-gray-300 text-gray-600' }}">
                All
            </button>
            @foreach ($tags as $tag)
                <button type="button" data-tag-id="{{ $tag->id }}"
                        class="tag-filter-btn badge border {{ (string) ($filters['tag'] ?? '') === (string) $tag->id ? 'bg-brand-600 text-white border-brand-600' : 'border-gray-300 text-gray-600' }}">
                    {{ $tag->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<div id="issues-list">
    @include('issues.partials.list')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/issue-filters.js') }}" defer></script>
@endpush
