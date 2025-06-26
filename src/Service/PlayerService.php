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
    ) {}

    public function joinEvent(string $inviteCode, CreatePlayerDto $dto): Player
    {
        $event = $this->eventRepository->findOneBy(['inviteCode' => $inviteCode]);

        if (!$event) {
            throw new NotFoundHttpException("Event not found.");
        }

        if ($event->getPlayers()->count() == $event->getMaxPlayers()) {
            throw new \RuntimeException('Maximum players reached.');
        }

        $player = new Player();
        $player->setName($dto->name);
        $player->setEvent($event);

        if (!empty($dto->preferences)) {
            foreach ($dto->preferences as $item) {
                // users can select multiple preferences
                $multiPrefs = \explode(',', $item['value']);
                foreach ($multiPrefs as $pref) {
                    $preference = new Preference();
                    $preference->setValue($pref);
                    $preference->setType(PreferenceTypeEnum::from($item['type']));
                    $preference->setPlayer($player);
                    $player->addPreference($preference);
                }
            }
        }

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    public function submitGift(int $playerId, SubmitGiftDto $dto): Gift
    {
        $player = $this->playerRepository->find($playerId);

        if (!$player) {
            throw new NotFoundHttpException("Player not found.");
        }

        $gift = new Gift();
        $gift->setTitle($dto->title);
        $gift->setCategory($dto->category);
        $gift->setPrice($dto->price);
        $gift->setProductUrl($dto->productUrl);
        $gift->setEvent($player->getEvent());
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
