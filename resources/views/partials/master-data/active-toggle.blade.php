@props([
    'active' => false,
    'url' => null,
    'editable' => true,
])

@if ($editable && $url)
    <label class="active-toggle" title="{{ $active ? 'Nonaktifkan' : 'Aktifkan' }}">
        <input
            type="checkbox"
            class="active-toggle-input"
            data-url="{{ $url }}"
            @checked($active)
        >
        <span class="active-toggle-slider" aria-hidden="true"></span>
        <span class="sr-only">{{ $active ? 'Aktif' : 'Nonaktif' }}</span>
    </label>
@else
    <span @class([
        'status-badge',
        'status-badge-active' => $active,
        'status-badge-inactive' => ! $active,
    ])>
        {{ $active ? 'Aktif' : 'Nonaktif' }}
    </span>
@endif

<span class="status-filter-value sr-only">{{ $active ? 'Aktif' : 'Nonaktif' }}</span>
