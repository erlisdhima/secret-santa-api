<?php
declare(strict_types=1);

namespace App\Enum;

enum EventStatusEnum: string
{
    case Running = 'running';
    case Completed = 'completed';
    case Abandoned = 'abandoned';
}
