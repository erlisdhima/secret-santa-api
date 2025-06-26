<?php
declare(strict_types=1);

namespace App\Controller;

use App\Enum\EventStatusEnum;
use App\Enum\PreferenceTypeEnum;
use App\Repository\EventRepository;
use App\Service\GiftAssignmentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventExchangeController extends AbstractController
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly GiftAssignmentService $giftAssignmentService,
    ) {}

    #[Route('/api/v1/events/{id}/run-gift-exchange', name: 'run_gift_exchange', methods: ['POST'])]
    public function runExchange(int $id): JsonResponse {
        $event = $this->eventRepository->find($id);

        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        if ($event->getStatus() != EventStatusEnum::Running) {
            return $this->json(['error' => 'Exchange cannot be run again or event is closed'], 400);
        }

        // remove players without a gift
        $players = $event->getPlayers()->filter(fn($p) => $p->getGift() !== null)->getValues();

        if (count($players) < 2) {
            return $this->json(['error' => 'Not enough players with gifts'], Response::HTTP_BAD_REQUEST);
        }

        // Run the assignment
        $this->giftAssignmentService->assign($event, $players);

        // Build response showing who received what
        $results = [];

        foreach ($event->getAssignments() as $assignment) {
            $receiver = $assignment->getReceiver();
            $gift = $assignment->getGift();

            $results[] = [
                'player'   => $receiver->getName(),
                'gift'     => $gift->getTitle(),
                'category' => $gift->getCategory(),
            ];
        }

        // TODO: cleanup/remove event & data (e.g. via a cron job based on an 'older than' amount of days)

        return $this->json(['results' => $results]);
    }

    #[Route('/api/v1/events/{id}/gift-assignments', name: 'get_gift_assignments', methods: ['GET'])]
    public function getExchangeResults(int $id): JsonResponse
    {
        $event = $this->eventRepository->find($id);

        return $this->json($event->getAssignments());
    }
}
