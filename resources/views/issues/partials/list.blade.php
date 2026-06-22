@if ($issues->isEmpty())
    <div class="text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-200">
        No issues match these filters.
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
        @foreach ($issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="flex items-center justify-between gap-4 p-4 hover:bg-gray-50">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ $issue->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">in {{ $issue->project->name }}</p>
                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        @include('partials.status-badge', ['status' => $issue->status])
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                        @foreach ($issue->tags as $tag)
                            @include('partials.tag-pill', ['tag' => $tag])
                        @endforeach
                    </div>
                </div>
                <div class="text-xs text-gray-400 shrink-0 text-right">
                    <p>{{ $issue->comments_count }} comment{{ $issue->comments_count === 1 ? '' : 's' }}</p>
                    @if ($issue->due_date)
                        <p class="mt-1">Due {{ $issue->due_date->format('M j') }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $issues->links() }}
    </div>
@endif
