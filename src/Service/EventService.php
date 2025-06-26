<?php

declare(strict_types=1);

namespace App\Service;

use App\Api\EventServiceInterface;
use App\Dto\CreateEventDto;
use App\Entity\Event;
use App\Factory\EventFactory;
use Doctrine\ORM\EntityManagerInterface;

readonly class EventService implements EventServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventFactory $eventFactory,
    ) {
    }

    public function createEvent(CreateEventDto $createEventDto): Event
    {
        $event = $this->eventFactory->create(
            $createEventDto->organizerName,
            $createEventDto->maxPlayers,
            $createEventDto->giftBudget,
        );

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}
