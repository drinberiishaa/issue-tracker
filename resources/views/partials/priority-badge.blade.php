@php
    $priorityConfig = [
        'low' => ['class' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20', 'icon' => 'M19 14l-7 7m0 0l-7-7m7 7V3'],
        'medium' => ['class' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20', 'icon' => 'M8 7h8m-8 5h8m-8 5h8'],
        'high' => ['class' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20', 'icon' => 'M5 10l7-7m0 0l7 7m-7-7v18'],
    ];
    $config = $priorityConfig[$priority] ?? ['class' => 'bg-gray-100 text-gray-600', 'icon' => ''];
@endphp
<span class="badge {{ $config['class'] }}">
    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/></svg>
    {{ ucfirst($priority) }}
</span>
