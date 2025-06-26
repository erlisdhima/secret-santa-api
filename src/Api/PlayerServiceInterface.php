<?php

declare(strict_types=1);

namespace App\Api;

use App\Dto\CreatePlayerDto;
use App\Dto\SubmitGiftDto;
use App\Entity\Gift;
use App\Entity\Player;

interface PlayerServiceInterface
{
    public function joinEvent(string $inviteCode, CreatePlayerDto $dto): Player;

    public function submitGift(int $eventId, int $playerId, SubmitGiftDto $dto): Gift;
}
