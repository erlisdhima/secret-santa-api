<?php

declare(strict_types=1);

namespace Service;

use App\Entity\Event;
use App\Entity\Gift;
use App\Entity\Player;
use App\Entity\Preference;
use App\Enum\PreferenceTypeEnum;
use App\Repository\PreferenceRepository;
use App\Service\GiftAssignmentService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class GiftAssignmentServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAssignGiftsCreatesCorrectAssignments(): void
    {
        // create game
        $event = new Event();
        $event->setGiftBudget(50);
        $event->setMaxPlayers(3);
        $event->setOrganizerName('Test');
        $event->setInviteCode('TEST123');

        // modify player id at runtime
        $reflection = new \ReflectionClass(Player::class);
        $playerId = $reflection->getProperty('id');
        $playerId->setAccessible(true);

        // Create players
        $erlis = (new Player())->setName('Erlis')->setEvent($event);
        $playerId->setValue($erlis, 1);
        $alice = (new Player())->setName('Alice')->setEvent($event);
        $playerId->setValue($alice, 2);
        $bob = (new Player())->setName('Bob')->setEvent($event);
        $playerId->setValue($bob, 3);

        // Add include/exclude preferences (leave one user without preferences)
        $erlisPref = (new Preference())->setValue('Books')->setType(PreferenceTypeEnum::Include)->setPlayer($alice);
        $erlis->addPreference($erlisPref);
        $bobPref = (new Preference())->setValue('Tech')->setType(PreferenceTypeEnum::Exclude)->setPlayer($bob);
        $bob->addPreference($bobPref);

        // modify gift id at runtime
        $reflection = new \ReflectionClass(Gift::class);
        $giftId = $reflection->getProperty('id');
        $giftId->setAccessible(true);

        // Create gifts
        $erlisGift = (new Gift())->setTitle('Harry Potter')->setCategory('Books')->setPrice(20)->setEvent($event)->setGiver($erlis);
        $giftId->setValue($erlisGift, 1);
        $aliceGift = (new Gift())->setTitle('Lego Set')->setCategory('Toys')->setPrice(30)->setEvent($event)->setGiver($alice);
        $giftId->setValue($aliceGift, 2);
        $bobGift = (new Gift())->setTitle('USB Stick')->setCategory('Tech')->setPrice(25)->setEvent($event)->setGiver($bob);
        $giftId->setValue($bobGift, 3);

        // Add to event
        $event->getPlayers()->add($erlis);
        $event->getPlayers()->add($alice);
        $event->getPlayers()->add($bob);
        $event->getGifts()->add($erlisGift);
        $event->getGifts()->add($aliceGift);
        $event->getGifts()->add($bobGift);

        // Run assignment
        $preferenceRepository = $this->createMock(PreferenceRepository::class);
        $preferenceRepository->method('getPlayerPreferences')
            ->willReturnCallback(
                fn (Player $player, PreferenceTypeEnum $type) => match (true) {
                    $player === $erlis && PreferenceTypeEnum::Include === $type => [$erlisPref],
                    $player === $bob && PreferenceTypeEnum::Exclude === $type => [$bobPref],
                    default => [],
                }
            );

        $preferenceRepository->method('getPlayerPreferences')->willReturnMap(
            [
                [$erlis, PreferenceTypeEnum::Include, [$erlisPref]],
                [$bob, PreferenceTypeEnum::Exclude, [$bobPref]],
            ]
        );
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $service = new GiftAssignmentService($entityManager, $preferenceRepository);
        $assignments = $service->generateAssignments($event, [$erlis, $alice, $bob]);

        // Assertions
        $this->assertCount(3, $assignments, 'Each player should get one gift');

        $giftIds = [];
        $receiverIds = [];

        foreach ($assignments as $assignment) {
            $gift = $assignment->getGift();
            $receiver = $assignment->getReceiver();

            $this->assertNotSame($gift->getGiver(), $receiver, 'No one should receive their own gift');

            $giftIds[] = $gift->getId();
            $receiverIds[] = $receiver->getId();
        }

        $this->assertCount(3, array_unique($giftIds), 'No gift should be assigned more than once');
        $this->assertCount(3, array_unique($receiverIds), 'Each receiver should be unique');
    }
}
