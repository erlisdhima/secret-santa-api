<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Event;
use App\Enum\EventStatusEnum;
use App\Service\InviteCodeGenerator;

class EventFactory
{
    public function __construct(private readonly InviteCodeGenerator $inviteCodeGenerator)
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
