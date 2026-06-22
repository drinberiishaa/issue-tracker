@php
    $statusConfig = [
        'open' => ['class' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'in_progress' => ['class' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'closed' => ['class' => 'bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-500/20', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    $config = $statusConfig[$status] ?? ['class' => 'bg-gray-100 text-gray-600', 'icon' => ''];
    $label = str($status)->replace('_', ' ')->title();
@endphp
<span class="badge {{ $config['class'] }}">
    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/></svg>
    {{ $label }}
</span>
