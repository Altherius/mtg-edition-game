<?php

namespace App\Repository;

use App\Entity\HighScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HighScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method HighScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method HighScore[]    findAll()
 * @method HighScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HighScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HighScore::class);
    }

    public function findGroupedByUser()
    {
        $qb = $this
            ->createQueryBuilder('h')
            ->addSelect('MAX(h.score) as maxScore')
            ->groupBy('h.user')
        ;

        return $qb->getQuery()->getResult();
    }
}
