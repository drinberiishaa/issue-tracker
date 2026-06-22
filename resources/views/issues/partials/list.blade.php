@if ($issues->isEmpty())
    <div class="empty-state text-center py-16 bg-white rounded-2xl border border-gray-200 shadow-soft">
        <div class="flex justify-center mb-3">
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </span>
        </div>
        <p class="text-gray-500 font-medium">No issues match these filters</p>
        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100 shadow-soft overflow-hidden">
        @foreach ($issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="issue-row flex items-center justify-between gap-4 p-4 sm:px-6">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $issue->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        {{ $issue->project->name }}
                    </p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @include('partials.status-badge', ['status' => $issue->status])
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                        @foreach ($issue->tags as $tag)
                            @include('partials.tag-pill', ['tag' => $tag])
                        @endforeach
                    </div>
                </div>
                <div class="text-xs text-gray-400 shrink-0 text-right space-y-1">
                    <p class="flex items-center gap-1 justify-end">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $issue->comments_count }}
                    </p>
                    @if ($issue->due_date)
                        <p>Due {{ $issue->due_date->format('M j') }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $issues->links() }}
    </div>
@endif
