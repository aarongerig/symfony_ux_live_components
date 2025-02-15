<?php

declare(strict_types=1);

namespace App\Enum;

enum PremiumType: string
{
    case BUILDING = 'building';
    case CONSTRUCTION = 'construction';
}
