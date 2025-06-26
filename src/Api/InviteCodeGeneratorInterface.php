<?php

declare(strict_types=1);

namespace App\Api;

interface InviteCodeGeneratorInterface
{
    public function generate(int $length = 8): string;
}
