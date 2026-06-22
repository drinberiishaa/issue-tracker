@extends('layouts.app')

@section('title', 'Tags')

@section('content')
<div class="flex items-start sm:items-center justify-between gap-3 mb-6 sm:mb-8">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tags</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Organize issues with color-coded tags</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @if ($tags->isEmpty())
            <div class="empty-state text-center py-20 bg-white rounded-2xl border border-gray-200 shadow-soft">
                <div class="flex justify-center mb-4">
                    <span class="flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-50 text-brand-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                </div>
                <p class="text-gray-500 font-medium">No tags yet</p>
                <p class="text-sm text-gray-400 mt-1">Create your first tag to start organizing issues</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100 shadow-soft overflow-hidden">
                @foreach ($tags as $tag)
                    <div class="flex items-center justify-between p-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $tag->color ?? '#6B7280' }};"></span>
                            <span class="badge shadow-sm" style="background-color: {{ $tag->color ?? '#6B7280' }}15; color: {{ $tag->color ?? '#6B7280' }}; border: 1px solid {{ $tag->color ?? '#6B7280' }}30;">
                                {{ $tag->name }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-400 font-medium">{{ $tag->issues_count }} issue{{ $tag->issues_count === 1 ? '' : 's' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @auth
    <div>
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-soft sticky top-24">
            <div class="flex items-center gap-3 mb-5">
                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 text-brand-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </span>
                <h2 class="font-bold text-gray-900">New Tag</h2>
            </div>

            @include('partials.errors')

            <form method="POST" action="{{ route('tags.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Bug, Feature, Urgent"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1.5">Color</label>
                    <div class="flex items-center gap-3">
                        <input id="color" name="color" type="color" value="{{ old('color', '#6366F1') }}"
                               class="w-12 h-10 rounded-xl border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-400">Choose a color for the tag</span>
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Tag
                </button>
            </form>
        </div>
    </div>
    @endauth
</div>
@endsection
