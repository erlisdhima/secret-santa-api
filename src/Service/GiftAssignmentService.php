<?php

declare(strict_types=1);

namespace App\Service;

use App\Api\GiftAssignmentServiceInterface;
use App\Entity\Event;
use App\Entity\GiftAssignment;
use App\Enum\EventStatusEnum;
use App\Enum\PreferenceTypeEnum;
use App\Repository\PreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class GiftAssignmentService implements GiftAssignmentServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PreferenceRepository $preferenceRepository,
    ) {
    }

    /**
     * Batch assigns gifts to all players.
     */
    public function assign(Event $event, ?array $players = null): void
    {
        $assignments = $this->generateAssignments($event, $players);

        foreach ($assignments as $a) {
            $this->entityManager->persist($a);
        }

        $event->setStatus(EventStatusEnum::Completed);
        $this->entityManager->flush();
    }

    public function generateAssignments(Event $event, ?array $players): array
    {
        $availableGifts = $event->getGifts()->toArray();
        $assignments = [];

        if (!$players) {
            $players = $event->getPlayers();
        }

        foreach ($players as $player) {
            $playerPrefsInclude = $this->preferenceRepository
                ->getPlayerPreferences($player, PreferenceTypeEnum::Include);
            $playerPrefsExclude = $this->preferenceRepository
                ->getPlayerPreferences($player, PreferenceTypeEnum::Exclude);

            // Exclude gifts from self
            $playerId = $player->getId();
            $validGifts = array_filter($availableGifts, fn ($g) => $g->getGiver()?->getId() !== $playerId);

            // Prefer gifts matching preferences
            $preferred = array_filter(
                $validGifts,
                function ($gift) use ($playerPrefsInclude, $playerPrefsExclude) {
                    return in_array($gift->getCategory(), $playerPrefsInclude, true)
                        && !in_array($gift->getCategory(), $playerPrefsExclude, true);
                });

            // If no preferred gift, fall back to random
            $finalGifts = $preferred ?: $validGifts;
            if (empty($finalGifts)) {
                continue;
            }

            // Assign gift to player
            $selected = $finalGifts[array_rand($finalGifts)];
            $assignment = new GiftAssignment();
            $assignment->setEvent($event);
            $assignment->setGift($selected);
            $assignment->setReceiver($player);

            $assignments[] = $assignment;
            $availableGifts = array_filter($availableGifts, fn ($g) => $g !== $selected);
        }

        return $assignments;
    }
}
