@extends('layouts.app')

@section('title', $issue->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.show', $issue->project) }}" class="text-sm text-gray-500 hover:text-gray-700">
        &larr; {{ $issue->project->name }}
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-3">
                <h1 class="text-2xl font-bold">{{ $issue->title }}</h1>
                @auth
                    <a href="{{ route('issues.edit', $issue) }}" class="text-sm border border-gray-300 px-3 py-1.5 rounded-md hover:bg-gray-50 shrink-0">Edit</a>
                @endauth
            </div>

            <div class="flex items-center gap-2 mb-4 flex-wrap">
                @include('partials.status-badge', ['status' => $issue->status])
                @include('partials.priority-badge', ['priority' => $issue->priority])
                @if ($issue->due_date)
                    <span class="text-xs text-gray-500">Due {{ $issue->due_date->format('M j, Y') }}</span>
                @endif
            </div>

            <p class="text-gray-700 whitespace-pre-line">{{ $issue->description ?: 'No description provided.' }}</p>
        </div>

        <!-- Tags section with AJAX attach/detach -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold">Tags</h2>
                @auth
                    <button type="button" id="open-tag-modal" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                        + Manage tags
                    </button>
                @endauth
            </div>
            <div id="issue-tags-list" class="flex flex-wrap gap-2" data-issue-id="{{ $issue->id }}">
                @forelse ($issue->tags as $tag)
                    <span class="badge tag-pill-removable" style="background-color: {{ $tag->color ?? '#6B7280' }}1A; color: {{ $tag->color ?? '#6B7280' }};" data-tag-id="{{ $tag->id }}">
                        {{ $tag->name }}
                        @auth
                            <button type="button" class="detach-tag-btn ml-1.5 hover:opacity-60" data-tag-id="{{ $tag->id }}" title="Remove tag">&times;</button>
                        @endauth
                    </span>
                @empty
                    <p class="text-sm text-gray-400" id="no-tags-msg">No tags attached.</p>
                @endforelse
            </div>
        </div>

        @auth
        <!-- Members section with AJAX attach/detach (bonus) -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold">Assigned Members</h2>
                <button type="button" id="open-member-modal" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                    + Manage members
                </button>
            </div>
            <div id="issue-members-list" class="flex flex-wrap gap-2" data-issue-id="{{ $issue->id }}">
                @forelse ($issue->members as $member)
                    <span class="badge bg-indigo-50 text-indigo-700 member-pill-removable" data-user-id="{{ $member->id }}">
                        {{ $member->name }}
                        <button type="button" class="detach-member-btn ml-1.5 hover:opacity-60" data-user-id="{{ $member->id }}" title="Unassign">&times;</button>
                    </span>
                @empty
                    <p class="text-sm text-gray-400" id="no-members-msg">No members assigned.</p>
                @endforelse
            </div>
        </div>
        @endauth

        <!-- Comments section with AJAX pagination and posting -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold mb-4">Comments</h2>

            <form id="comment-form" data-issue-id="{{ $issue->id }}" class="space-y-3 mb-6 pb-6 border-b border-gray-100">
                <div id="comment-errors" class="hidden rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                    <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5"></ul>
                </div>
                <div>
                    <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Your name</label>
                    <input id="author_name" name="author_name" type="text"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                    <textarea id="body" name="body" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                </div>
                <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-brand-700">
                    <span class="submit-label">Post Comment</span>
                </button>
            </form>

            <div id="comments-list" class="space-y-4"></div>
            <div id="comments-pagination" class="mt-4 text-center"></div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold mb-3 text-sm text-gray-500 uppercase tracking-wide">Details</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Project</dt>
                    <dd><a href="{{ route('projects.show', $issue->project) }}" class="text-brand-600">{{ $issue->project->name }}</a></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Created</dt>
                    <dd>{{ $issue->created_at->format('M j, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Updated</dt>
                    <dd>{{ $issue->updated_at->diffForHumans() }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Tag management modal -->
<div id="tag-modal" class="fixed inset-0 bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg">Manage Tags</h3>
            <button type="button" id="close-tag-modal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <div class="mb-4">
            <p class="text-xs font-medium text-gray-500 mb-2">Click a tag to attach / detach it.</p>
            <div id="modal-all-tags" class="flex flex-wrap gap-2">
                @foreach ($allTags as $tag)
                    <button type="button" class="modal-tag-toggle badge border border-gray-300" data-tag-id="{{ $tag->id }}" data-tag-name="{{ $tag->name }}" data-tag-color="{{ $tag->color }}">
                        {{ $tag->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <p class="text-xs font-medium text-gray-500 mb-2">Or create a new tag</p>
            <div class="flex gap-2">
                <input type="text" id="new-tag-name" placeholder="Tag name" class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-brand-500 focus:ring-brand-500">
                <input type="color" id="new-tag-color" value="#6366F1" class="w-10 h-9 rounded border-gray-300">
                <button type="button" id="create-tag-btn" class="bg-brand-600 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:bg-brand-700">Add</button>
            </div>
            <p id="new-tag-error" class="text-xs text-red-600 mt-1 hidden"></p>
        </div>
    </div>
</div>

@auth
<!-- Member management modal -->
<div id="member-modal" class="fixed inset-0 bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg">Manage Members</h3>
            <button type="button" id="close-member-modal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <p class="text-xs font-medium text-gray-500 mb-2">Click a member to assign / unassign them.</p>
        <div id="modal-all-members" class="flex flex-wrap gap-2">
            @foreach ($allUsers as $user)
                <button type="button" class="modal-member-toggle badge border border-gray-300" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                    {{ $user->name }}
                </button>
            @endforeach
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script src="{{ asset('js/issue-tags.js') }}" defer></script>
<script src="{{ asset('js/issue-members.js') }}" defer></script>
<script src="{{ asset('js/issue-comments.js') }}" defer></script>
@endpush
