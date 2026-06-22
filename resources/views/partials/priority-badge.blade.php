@php
    $priorityStyles = [
        'low' => 'bg-emerald-100 text-emerald-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'high' => 'bg-red-100 text-red-700',
    ];
@endphp
<span class="badge {{ $priorityStyles[$priority] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($priority) }}</span>
