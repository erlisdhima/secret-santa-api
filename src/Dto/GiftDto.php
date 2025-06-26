<?php
declare(strict_types=1);

namespace App\Dto;

readonly class GiftDto
{
    public function __construct(
        public string $title,
        public string $category,
        public float $price,
        public ?string $productUrl,
        public ?string $giverName,
    ) {}
}
