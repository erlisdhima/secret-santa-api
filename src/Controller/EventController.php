<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateEventDto;
use App\Dto\GiftDto;
use App\Entity\Event;
use App\Entity\Gift;
use App\Entity\Player;
use App\Repository\EventRepository;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends AbstractController
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly EventRepository $eventRepository,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/v1/events', name: 'create_event', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $this->serializer->deserialize($request->getContent(), CreateEventDto::class, 'json');

        if (!isset($data->organizerName, $data->maxPlayers, $data->giftBudget)) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 422);
        }

        $event = $this->eventService->createEvent($data);

        return $this->json($this->getEventData($event), 201);
    }

    #[Route('/api/v1/events', name: 'get_events_list', methods: ['GET'])]
    public function getList(): JsonResponse
    {
        $events = $this->eventRepository->findAll();
        $eventsArray = [];
        foreach ($events as $event) {
            $eventsArray[] = $this->getEventData($event);
        }

        return $this->json($eventsArray);
    }

    #[Route('/api/v1/events/{inviteCode}', name: 'get_event_by_invite_code', methods: ['GET'])]
    public function getByInviteCode(string $inviteCode): JsonResponse
    {
        $event = $this->eventRepository->findOneBy(['inviteCode' => $inviteCode]);

        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        return $this->json($this->getEventData($event));
    }

    #[Route('/api/v1/events/{id}/players', name: 'get_event_players', methods: ['GET'])]
    public function getEventPlayers(int $id): JsonResponse
    {
        $events = $this->eventRepository->findBy(['id' => $id]);
        $event = \reset($events);
        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        $players = array_map(
            fn (Player $player) => [
                'name' => $player->getName(),
                'preferences' => $player->getPreferences()
                    ->map(fn ($p) => [
                        'value' => $p->getValue(),
                        'type' => $p->getType()->value,
                    ])
                    ->toArray(),
                'gift' => null !== $player->getGift()
                    ? new GiftDto(
                        $player->getGift()->getTitle(),
                        $player->getGift()->getCategory(),
                        $player->getGift()->getPrice(),
                        $player->getGift()->getProductUrl(),
                        $player->getGift()->getGiver()?->getName(),
                    )
                    : null,
            ],
            $event->getPlayers()->toArray()
        );

        return $this->json($players);
    }

    #[Route('/api/v1/events/{id}/gifts', name: 'get_submitted_event_gifts', methods: ['GET'])]
    public function getEventGifts(Event $event): JsonResponse
    {
        $gifts = array_map(
            fn (Gift $gift) => new GiftDto(
                $gift->getTitle(),
                $gift->getCategory(),
                $gift->getPrice(),
                $gift->getProductUrl(),
                $gift->getGiver()->getName(),
            ),
            $event->getGifts()->toArray()
        );

        return $this->json($gifts);
    }

    private function getEventData(Event $event): array
    {
        return [
            'id' => $event->getId(),
            'organizerName' => $event->getOrganizerName(),
            'inviteCode' => $event->getInviteCode(),
            'status' => $event->getStatus()->value,
            'maxPlayers' => $event->getMaxPlayers(),
            'giftBudget' => $event->getGiftBudget(),
            'createdAt' => $event->getCreatedAt(),
        ];
    }
}
