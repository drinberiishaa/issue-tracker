@extends('layouts.app')

@section('title', 'Issues')

@section('content')
<div class="flex items-start sm:items-center justify-between gap-3 mb-6 sm:mb-8">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Issues</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Browse and filter all issues</p>
    </div>
    @auth
        <a href="{{ route('issues.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Issue
        </a>
    @endauth
</div>

<div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-soft">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Search</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="issue-search" value="{{ $filters['q'] ?? '' }}" placeholder="Search by title or description..."
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status</label>
            <select id="filter-status" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                <option value="">All statuses</option>
                @foreach (\App\Models\Issue::STATUSES as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Priority</label>
            <select id="filter-priority" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                <option value="">All priorities</option>
                @foreach (\App\Models\Issue::PRIORITIES as $priority)
                    <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-100">
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Filter by tag</label>
        <div class="flex flex-wrap gap-2" id="filter-tags">
            <button type="button" data-tag-id="" class="tag-filter-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all {{ empty($filters['tag']) ? 'bg-brand-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All
            </button>
            @foreach ($tags as $tag)
                <button type="button" data-tag-id="{{ $tag->id }}"
                        class="tag-filter-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all {{ (string) ($filters['tag'] ?? '') === (string) $tag->id ? 'bg-brand-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
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
