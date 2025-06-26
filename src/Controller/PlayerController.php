<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreatePlayerDto;
use App\Dto\SubmitGiftDto;
use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerController extends AbstractController
{
    public function __construct(
        private readonly PlayerService $playerService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/v1/events/join/{inviteCode}', name: 'player_join', methods: ['POST'])]
    public function join(string $inviteCode, Request $request): JsonResponse
    {
        $data = $this->serializer->deserialize($request->getContent(), CreatePlayerDto::class, 'json');

        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $player = $this->playerService->joinEvent($inviteCode, $data);

        return $this->json([
            'message' => 'Player joined successfully.',
            'player_id' => $player->getId(),
            'name' => $player->getName(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/v1/events/{id}/gift', name: 'player_submit_gift', methods: ['POST'])]
    public function submitGift(int $id, Request $request): JsonResponse
    {
        $data = $this->serializer->deserialize($request->getContent(), SubmitGiftDto::class, 'json');

        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $gift = $this->playerService->submitGift($id, $data->playerId, $data);

        return $this->json([
            'message' => 'Gift submitted successfully.',
            'gift_id' => $gift->getId(),
        ]);
    }

    #[Route('/api/v1/player/{id}', name: 'get_player', methods: ['GET'])]
    public function getPlayer(int $id): JsonResponse
    {
        $player = $this->playerService->getPlayer($id);

        return $this->json([
            'id' => $player->getId(),
            'name' => $player->getName(),
            'event' => $player->getEvent()->getInviteCode(),
            'preferences' => array_map(fn($p) => [
                'type' => $p->getType()->value,
                'value' => $p->getValue(),
            ], $player->getPreferences()->toArray())
        ]);
    }
}
