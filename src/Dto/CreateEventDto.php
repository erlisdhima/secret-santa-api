<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateEventDto
{
    #[Assert\NotBlank]
    public string $organizerName;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $maxPlayers;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public float $giftBudget;

    public function __construct(string $organizerName, int $maxPlayers, float $giftBudget)
    {
        $this->organizerName = $organizerName;
        $this->maxPlayers = $maxPlayers;
        $this->giftBudget = $giftBudget;
    }
}
