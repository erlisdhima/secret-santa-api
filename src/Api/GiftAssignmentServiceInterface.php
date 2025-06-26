<?php

declare(strict_types=1);

namespace App\Api;

use App\Entity\Event;

interface GiftAssignmentServiceInterface
{
    public function assign(Event $event, ?array $players = null): void;

    public function generateAssignments(Event $event, ?array $players): array;
}
