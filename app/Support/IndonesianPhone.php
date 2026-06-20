<?php

namespace App\Support;

class IndonesianPhone
{
    public static function normalize(?string $input): ?string
    {
        if ($input === null || trim($input) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $input);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '62')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if ($digits === '') {
            return null;
        }

        return self::formatNational($digits);
    }

    public static function formatNational(string $digits): string
    {
        return '+62 ' . self::groupDigits($digits);
    }

    public static function toInputValue(?string $stored): string
    {
        if ($stored === null || trim($stored) === '') {
            return '';
        }

        $digits = preg_replace('/\D/', '', $stored);

        if (str_starts_with($digits, '62')) {
            $digits = substr($digits, 2);
        }

        return self::groupDigits($digits);
    }

    private static function groupDigits(string $digits): string
    {
        $length = strlen($digits);

        if ($length === 10 && str_starts_with($digits, '8')) {
            return substr($digits, 0, 3) . '-' . substr($digits, 3, 4) . '-' . substr($digits, 7);
        }

        $parts = [];

        while (strlen($digits) > 4) {
            $parts[] = substr($digits, 0, 4);
            $digits = substr($digits, 4);
        }

        if ($digits !== '') {
            $parts[] = $digits;
        }

        return implode('-', $parts);
    }
}
