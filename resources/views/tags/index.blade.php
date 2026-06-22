@extends('layouts.app')

@section('title', 'Tags')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tags</h1>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @if ($tags->isEmpty())
            <div class="text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-200">
                No tags yet.
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @foreach ($tags as $tag)
                    <div class="flex items-center justify-between p-4">
                        <span class="badge" style="background-color: {{ $tag->color ?? '#6B7280' }}1A; color: {{ $tag->color ?? '#6B7280' }};">
                            {{ $tag->name }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $tag->issues_count }} issue{{ $tag->issues_count === 1 ? '' : 's' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @auth
    <div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold mb-4">New Tag</h2>

            @include('partials.errors')

            <form method="POST" action="{{ route('tags.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input id="color" name="color" type="color" value="{{ old('color', '#6366F1') }}"
                           class="w-full h-10 rounded-md border-gray-300">
                </div>
                <button type="submit" class="w-full bg-brand-600 text-white py-2 rounded-md font-medium hover:bg-brand-700">
                    Create Tag
                </button>
            </form>
        </div>
    </div>
    @endauth
</div>
@endsection
