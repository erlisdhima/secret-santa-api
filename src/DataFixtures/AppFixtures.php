<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Gift;
use App\Entity\Player;
use App\Entity\Preference;
use App\Enum\EventStatusEnum;
use App\Enum\PreferenceTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $included = [
            'Books',
            'Electronics',
            'Fitness',
            'Games',
        ];

        $excluded = [
            'Accessories',
            'Food',
            'DYI',
            'Garden',
        ];

        $event = new Event();
        $event->setOrganizerName('Demo Organizer');
        $event->setInviteCode('DEMO1234');
        $event->setMaxPlayers(5);
        $event->setGiftBudget(30.00);
        $event->setStatus(EventStatusEnum::Running);
        $manager->persist($event);

        for ($i = 1; $i <= 5; $i++) {
            $player = new Player();
            $player->setName('Player ' . $i);
            $player->setEvent($event);
            $manager->persist($player);

            $randomIncluded = $included[array_rand($included)];
            $randomExcluded = $excluded[array_rand($excluded)];

            // include
            $pref = new Preference();
            $pref->setValue($randomIncluded);
            $pref->setType(PreferenceTypeEnum::Include);
            $pref->setPlayer($player);
            $manager->persist($pref);

            // exclude
            $pref = new Preference();
            $pref->setValue($randomExcluded);
            $pref->setType(PreferenceTypeEnum::Exclude);
            $pref->setPlayer($player);
            $manager->persist($pref);

            $gift = new Gift();
            $gift->setTitle("Gift from Player $i");
            $gift->setCategory('Books');
            $gift->setPrice(25.00);
            $gift->setEvent($event);
            $gift->setGiver($player);
            $manager->persist($gift);
        }

        $manager->flush();
    }
}
