<?php

namespace App\Enums;

enum ProductUnit: string
{
    case Pcs    = 'pcs';
    case Box    = 'box';
    case Pack   = 'pack';
    case Kg     = 'kg';
    case Gram   = 'gram';
    case Liter  = 'liter';
    case Ml     = 'ml';
    case Botol  = 'botol';
    case Kaleng = 'kaleng';
    case Sak    = 'sak';
    case Ikat   = 'ikat';
    case Lusin  = 'lusin';

    public function label(): string
    {
        return match ($this) {
            self::Pcs    => 'Pcs',
            self::Box    => 'Box / Dus',
            self::Pack   => 'Pack',
            self::Kg     => 'Kg',
            self::Gram   => 'Gram',
            self::Liter  => 'Liter',
            self::Ml     => 'Ml',
            self::Botol  => 'Botol',
            self::Kaleng => 'Kaleng',
            self::Sak    => 'Sak',
            self::Ikat   => 'Ikat',
            self::Lusin  => 'Lusin',
        };
    }
}
