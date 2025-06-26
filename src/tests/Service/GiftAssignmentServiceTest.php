<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\CreateEventDto;
use App\Entity\Preference;
use App\Enum\PreferenceTypeEnum;
use App\Factory\EventFactory;
use App\Factory\PreferenceFactory;
use App\Service\EventService;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Entity\Event;
use App\Entity\Player;
use App\Entity\Gift;
use App\Service\GiftAssignmentService;

class GiftAssignmentServiceTest extends TestCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventService $eventService,
        private PlayerService $playerService,
        private EventFactory $eventFactory,
        private PreferenceFactory $preferenceFactory,
    ) {}

    public function testAssignGiftsCreatesCorrectAssignments(): void
    {
        $event = new Event();
        $eventDto = new CreateEventDto(
            'Test Organiser',
            5,
            25
        );

        // should the factory use the DTO instead?
        $event = $this->eventFactory->create(
            'Test Organiser',
            5,
            25
        );

        for ($i = 0; $i < 3; $i++) {
            // better to use factories or not in test classes
            $player = new Player();
            $player->setName('Test Player ' . $i);
            $player->setEvent($event);

            $gift = new Gift();
            $gift->setTitle('Test Gift ' . $i);
            $gift->setCategory('Test Category ' . $i);
            $gift->setPrice(20);
            $gift->setGiver($player);
            $gift->setEvent($player->getEvent());
        }

        // add preference for the last player
        $preference = $this->preferenceFactory->create(
            'Books',
            PreferenceTypeEnum::Include,
            $player,
        );
        $player->addPreference($preference);

        $assignments = $service->assignGifts($event);

        $this->assertCount(3, $assignments);
    }
}
