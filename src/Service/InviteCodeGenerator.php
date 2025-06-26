<?php

declare(strict_types=1);

namespace App\Service;

use App\Api\InviteCodeGeneratorInterface;
use App\Repository\EventRepository;
use Symfony\Component\Uid\Ulid;

readonly class InviteCodeGenerator implements InviteCodeGeneratorInterface
{
    public function __construct(
        private EventRepository $eventRepository)
    {
    }

    public function generate(int $length = 8): string
    {
        do {
            $code = \substr(Ulid::generate(), 0, $length);
        } while ($this->eventRepository->findOneBy(['inviteCode' => $code]));

        return $code;
    }
}
