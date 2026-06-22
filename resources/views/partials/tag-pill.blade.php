@php
    $color = $tag->color ?? '#6B7280';
@endphp
<span class="badge shadow-sm" style="background-color: {{ $color }}15; color: {{ $color }}; border: 1px solid {{ $color }}30;">
    <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background-color: {{ $color }};"></span>
    {{ $tag->name }}
</span>
