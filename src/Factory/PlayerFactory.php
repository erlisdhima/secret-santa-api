<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Event;
use App\Entity\Player;

class PlayerFactory
{
    public function create(string $name, Event $event): Player
    {
        $player = new Player();
        $player->setName($name);
        $player->setEvent($event);

        return $player;
    }
}
