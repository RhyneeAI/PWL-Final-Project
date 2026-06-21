@props([
    'name',
    'value' => '',
    'required' => false,
    'placeholder' => 'dd/mm/yyyy',
    'id' => null,
    'inputClass' => 'w-full rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-gray-800 dark:text-white',
])

@php
    $ymdValue = '';

    if (filled($value)) {
        try {
            $ymdValue = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            $ymdValue = '';
        }
    }

    $displayValue = $ymdValue !== '' ? \Illuminate\Support\Carbon::parse($ymdValue)->format('d/m/Y') : '';
    $inputId = $id ?? 'date-' . preg_replace('/[^a-z0-9]/', '-', $name) . '-' . uniqid();
@endphp

<div data-date-picker>
    <input type="text"
        id="{{ $inputId }}"
        value="{{ $displayValue }}"
        placeholder="{{ $placeholder }}"
        class="date-picker-display {{ $inputClass }}"
        autocomplete="off"
        readonly
    >
    <input type="hidden" name="{{ $name }}" value="{{ $ymdValue }}" @if ($required) required @endif data-date-hidden>
</div>
