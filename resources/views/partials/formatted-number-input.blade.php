@props([
    'name',
    'value' => '',
    'prefix' => null,
    'placeholder' => '0',
])

@php
    $rawValue = old($name, $value);
    $numericValue = is_numeric($rawValue) ? (int) $rawValue : 0;
    $displayValue = $numericValue > 0
        ? number_format($numericValue, 0, ',', '.')
        : '';
@endphp

<div class="prefix-input-group" data-formatted-input-group>
    @if ($prefix)
        <span class="prefix-input-label">{{ $prefix }}</span>
    @endif
    <input
        type="text"
        class="prefix-input-field"
        data-formatted-display
        value="{{ $displayValue }}"
        placeholder="{{ $placeholder }}"
        inputmode="numeric"
        autocomplete="off"
    >
    <input type="hidden" name="{{ $name }}" data-formatted-hidden value="{{ $numericValue }}">
</div>
