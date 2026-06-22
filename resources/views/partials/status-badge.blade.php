@php
    $statusStyles = [
        'open' => 'bg-blue-100 text-blue-700',
        'in_progress' => 'bg-amber-100 text-amber-700',
        'closed' => 'bg-gray-200 text-gray-600',
    ];
    $label = str($status)->replace('_', ' ')->title();
@endphp
<span class="badge {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-600' }}">{{ $label }}</span>
