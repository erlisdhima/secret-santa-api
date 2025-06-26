<?php
declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SubmitGiftDto
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\GreaterThan(0)]
    public int $playerId;

    #[Assert\NotBlank]
    #[Assert\Length(max: 150)]
    public string $title;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    public string $category;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'numeric')]
    #[Assert\GreaterThan(0)]
    public float $price;

    #[Assert\Url]
    #[Assert\Length(max: 255)]
    public ?string $productUrl = null;

    public function __construct(
        int $playerId,
        string $title,
        string $category,
        float $price,
        ?string $productUrl = null,
    ) {
        $this->playerId = $playerId;
        $this->title = $title;
        $this->category = $category;
        $this->price = $price;
        $this->productUrl = $productUrl;
    }
}
