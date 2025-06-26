<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Preference;
use App\Enum\PreferenceTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Preference>
 */
class PreferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preference::class);
    }

    public function getPlayerPreferences(Player $player, PreferenceTypeEnum $type): array
    {
        $result = $this->createQueryBuilder('c')
            ->select('c.value')
            ->andWhere('c.player = :player AND c.type = :type')
            ->setParameter('player', $player->getId())
            ->setParameter('type', $type->value)
            ->getQuery()
            ->getResult();

        return array_column($result, 'value');
    }
}
