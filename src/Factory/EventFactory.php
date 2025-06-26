<?php

declare(strict_types=1);

namespace App\Factory;

use App\Api\InviteCodeGeneratorInterface;
use App\Entity\Event;
use App\Enum\EventStatusEnum;

class EventFactory
{
    public function __construct(private readonly InviteCodeGeneratorInterface $inviteCodeGenerator)
    {
    }

    public function create(string $organizerName, int $maxPlayers, float $giftBudget): Event
    {
        $event = new Event();
        $event->setOrganizerName($organizerName);
        $event->setMaxPlayers($maxPlayers);
        $event->setGiftBudget($giftBudget);
        $event->setStatus(EventStatusEnum::Running);
        $event->setInviteCode($this->inviteCodeGenerator->generate());
        $event->setCreatedAt(new \DateTimeImmutable());

        return $event;
    }
}
