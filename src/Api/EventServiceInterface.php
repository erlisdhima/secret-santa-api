<?php

declare(strict_types=1);

namespace App\Api;

use App\Dto\CreateEventDto;
use App\Entity\Event;

interface EventServiceInterface
{
    public function createEvent(CreateEventDto $createEventDto): Event;
}
