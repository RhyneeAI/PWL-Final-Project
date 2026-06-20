<?php

use App\Support\IndonesianPhone;

it('memformat nomor hp dengan prefix +62', function () {
    expect(IndonesianPhone::normalize('08212834999'))
        ->toBe('+62 821-2834-999');

    expect(IndonesianPhone::normalize('8212834999'))
        ->toBe('+62 821-2834-999');

    expect(IndonesianPhone::normalize('+62 821-2834-999'))
        ->toBe('+62 821-2834-999');
});

it('mengembalikan null untuk nomor kosong', function () {
    expect(IndonesianPhone::normalize(null))->toBeNull();
    expect(IndonesianPhone::normalize(''))->toBeNull();
});
