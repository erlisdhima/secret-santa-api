<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlayerDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public string $name;

    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'type' => new Assert\Choice(['include', 'exclude']),
                'value' => new Assert\NotBlank(),
            ],
            'allowMissingFields' => false,
        ]),
    ])]
    public ?array $preferences = [];

    public function __construct(string $name, array $preferences)
    {
        $this->name = $name;
        $this->preferences = $preferences;
    }
}
