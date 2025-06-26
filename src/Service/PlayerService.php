<?php
declare(strict_types=1);

namespace App\Service;

use App\Api\PlayerServiceInterface;
use App\Dto\CreatePlayerDto;
use App\Dto\SubmitGiftDto;
use App\Entity\Gift;
use App\Entity\Player;
use App\Entity\Preference;
use App\Enum\PreferenceTypeEnum;
use App\Factory\PlayerFactory;
use App\Factory\PreferenceFactory;
use App\Repository\EventRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PlayerService implements PlayerServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventRepository $eventRepository,
        private PlayerRepository $playerRepository,
        private PlayerFactory $playerFactory,
        private PreferenceFactory $preferenceFactory,
    ) {}

    public function joinEvent(string $inviteCode, CreatePlayerDto $playerDto): Player
    {
        $event = $this->eventRepository->findOneBy(['inviteCode' => $inviteCode]);

        if (!$event) {
            throw new NotFoundHttpException("Event not found.");
        }

        if ($event->getPlayers()->count() == $event->getMaxPlayers()) {
            throw new \RuntimeException('Maximum players reached.');
        }

        $player = $this->playerFactory->create($playerDto->name, $event);

        if (!empty($playerDto->preferences)) {
            foreach ($playerDto->preferences as $item) {
                // users can select multiple preferences
                $multiPrefs = \explode(',', $item['value']);
                foreach ($multiPrefs as $pref) {
                    $preference = $this->preferenceFactory->create(
                        $pref,
                        PreferenceTypeEnum::from($item['type']),
                        $player,
                    );
                    $player->addPreference($preference);
                }
            }
        }

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    public function submitGift(int $eventId, int $playerId, SubmitGiftDto $dto): Gift
    {
        $event = $this->eventRepository->find($eventId);
        $player = $this->playerRepository->find($playerId);

        if (!$player) {
            throw new NotFoundHttpException("Player not found.");
        }

        $gift = new Gift();
        $gift->setTitle($dto->title);
        $gift->setCategory($dto->category);
        $gift->setPrice($dto->price);
        $gift->setProductUrl($dto->productUrl);
        $gift->setEvent($event);
        $gift->setGiver($player);

        $this->entityManager->persist($gift);
        $this->entityManager->flush();

        return $gift;
    }

    public function getPlayer(int $id): Player
    {
        $player = $this->playerRepository->find($id);

        if (!$player) {
            throw new NotFoundHttpException("Player not found.");
        }

        return $player;
    }
}
