<?php
declare(strict_types=1);

namespace App\Api;

use App\Entity\Event;

interface GiftAssignmentServiceInterface
{
    public function assign(Event $event, array $players): void;
}
