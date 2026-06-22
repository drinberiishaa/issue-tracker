@extends('layouts.app')

@section('title', $issue->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.show', $issue->project) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-600 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        {{ $issue->project->name }}
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Issue header -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-8 shadow-soft">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-4 mb-4">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $issue->title }}</h1>
                @auth
                    <a href="{{ route('issues.edit', $issue) }}" class="btn-secondary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                @endauth
            </div>

            <div class="flex items-center gap-2 mb-5 flex-wrap">
                @include('partials.status-badge', ['status' => $issue->status])
                @include('partials.priority-badge', ['priority' => $issue->priority])
                @if ($issue->due_date)
                    <span class="badge bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Due {{ $issue->due_date->format('M j, Y') }}
                    </span>
                @endif
            </div>

            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                <p class="whitespace-pre-line">{{ $issue->description ?: 'No description provided.' }}</p>
            </div>
        </div>

        <!-- Tags section -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6 shadow-soft">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Tags
                </h2>
                @auth
                    <button type="button" id="open-tag-modal" class="text-sm text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Manage
                    </button>
                @endauth
            </div>
            <div id="issue-tags-list" class="flex flex-wrap gap-2" data-issue-id="{{ $issue->id }}">
                @forelse ($issue->tags as $tag)
                    <span class="badge tag-pill-removable shadow-sm" style="background-color: {{ $tag->color ?? '#6B7280' }}15; color: {{ $tag->color ?? '#6B7280' }}; border: 1px solid {{ $tag->color ?? '#6B7280' }}30;" data-tag-id="{{ $tag->id }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background-color: {{ $tag->color ?? '#6B7280' }};"></span>
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
        <!-- Members section -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6 shadow-soft">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Assigned Members
                </h2>
                <button type="button" id="open-member-modal" class="text-sm text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Manage
                </button>
            </div>
            <div id="issue-members-list" class="flex flex-wrap gap-2" data-issue-id="{{ $issue->id }}">
                @forelse ($issue->members as $member)
                    <span class="badge bg-brand-50 text-brand-700 ring-1 ring-inset ring-brand-200 member-pill-removable" data-user-id="{{ $member->id }}">
                        <span class="flex items-center justify-center w-4 h-4 rounded-full bg-brand-200 text-brand-800 text-[9px] font-bold mr-1.5">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        {{ $member->name }}
                        <button type="button" class="detach-member-btn ml-1.5 hover:opacity-60" data-user-id="{{ $member->id }}" title="Unassign">&times;</button>
                    </span>
                @empty
                    <p class="text-sm text-gray-400" id="no-members-msg">No members assigned.</p>
                @endforelse
            </div>
        </div>
        @endauth

        <!-- Comments section -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-8 shadow-soft">
            <h2 class="font-semibold text-gray-900 mb-5">Comments</h2>

            <form id="comment-form" data-issue-id="{{ $issue->id }}" class="space-y-4 mb-6 pb-6 border-b border-gray-100">
                <div id="comment-errors" class="hidden rounded-xl bg-red-50 border border-red-200 px-5 py-4">
                    <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5"></ul>
                </div>
                <div>
                    <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1.5">Your name</label>
                    <input id="author_name" name="author_name" type="text" placeholder="Enter your name"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
                </div>
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1.5">Comment</label>
                    <textarea id="body" name="body" rows="3" placeholder="Write a comment..."
                              class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4"></textarea>
                </div>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span class="submit-label">Post Comment</span>
                </button>
            </form>

            <div id="comments-list" class="space-y-4"></div>
            <div id="comments-pagination" class="mt-4 text-center"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6 shadow-soft">
            <h2 class="font-semibold text-xs text-gray-500 uppercase tracking-wider mb-4">Details</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <dt class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        Project
                    </dt>
                    <dd><a href="{{ route('projects.show', $issue->project) }}" class="text-brand-600 font-medium hover:text-brand-700 transition-colors">{{ $issue->project->name }}</a></dd>
                </div>
                <div class="border-t border-gray-100"></div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Created
                    </dt>
                    <dd class="text-gray-700 font-medium">{{ $issue->created_at->format('M j, Y') }}</dd>
                </div>
                <div class="border-t border-gray-100"></div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Updated
                    </dt>
                    <dd class="text-gray-700 font-medium">{{ $issue->updated_at->diffForHumans() }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Tag management modal -->
<div id="tag-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-elevated max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Manage Tags
            </h3>
            <button type="button" id="close-tag-modal" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="mb-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2.5">Click to attach / detach</p>
            <div id="modal-all-tags" class="flex flex-wrap gap-2">
                @foreach ($allTags as $tag)
                    <button type="button" class="modal-tag-toggle badge border border-gray-200 hover:border-gray-300 transition-colors cursor-pointer" data-tag-id="{{ $tag->id }}" data-tag-name="{{ $tag->name }}" data-tag-color="{{ $tag->color }}">
                        {{ $tag->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="pt-5 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2.5">Create new tag</p>
            <div class="flex gap-2">
                <input type="text" id="new-tag-name" placeholder="Tag name" class="flex-1 rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3">
                <input type="color" id="new-tag-color" value="#6366F1" class="w-10 h-9 rounded-lg border-gray-300 cursor-pointer">
                <button type="button" id="create-tag-btn" class="btn-primary py-2 px-3">Add</button>
            </div>
            <p id="new-tag-error" class="text-xs text-red-600 mt-1.5 hidden"></p>
        </div>
    </div>
</div>

@auth
<!-- Member management modal -->
<div id="member-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-elevated max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Manage Members
            </h3>
            <button type="button" id="close-member-modal" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2.5">Click to assign / unassign</p>
        <div id="modal-all-members" class="flex flex-wrap gap-2">
            @foreach ($allUsers as $user)
                <button type="button" class="modal-member-toggle badge border border-gray-200 hover:border-gray-300 transition-colors cursor-pointer" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                    <span class="flex items-center justify-center w-4 h-4 rounded-full bg-gray-200 text-gray-600 text-[9px] font-bold mr-1.5">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
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
