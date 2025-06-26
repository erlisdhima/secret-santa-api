<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Player;
use App\Entity\Preference;
use App\Enum\PreferenceTypeEnum;

class PreferenceFactory
{
    public function create(string $value, PreferenceTypeEnum $type, Player $player): Preference
    {
        $preference = new Preference();
        $preference->setValue($value);
        $preference->setType($type);
        $preference->setPlayer($player);

        return $preference;
    }
}
