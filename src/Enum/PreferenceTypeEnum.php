<?php

declare(strict_types=1);

namespace App\Enum;

enum PreferenceTypeEnum: string
{
    case Include = 'include';
    case Exclude = 'exclude';
}
