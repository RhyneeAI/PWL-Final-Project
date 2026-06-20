@props([
    'value' => '',
    'name' => 'phone',
])

@php
    $inputValue = old($name, $value);
    $storedPhone = $inputValue ? \App\Support\IndonesianPhone::normalize($inputValue) : '';
    $displayPhone = $inputValue ? \App\Support\IndonesianPhone::toInputValue($inputValue) : '';
@endphp

<div class="phone-input-group" data-phone-input-group>
    <span class="phone-input-prefix">+62</span>
    <input
        type="tel"
        class="phone-input-national"
        data-phone-national
        value="{{ $displayPhone }}"
        placeholder="821-2834-999"
        autocomplete="tel"
        inputmode="numeric"
    >
    <input type="hidden" name="{{ $name }}" class="phone-input-hidden" data-phone-hidden value="{{ $storedPhone }}">
</div>
