@php
    $color = $tag->color ?? '#6B7280';
@endphp
<span class="badge" style="background-color: {{ $color }}1A; color: {{ $color }};">
    {{ $tag->name }}
</span>
