<?php

namespace App\Repository;

use App\Entity\HighScore;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function findHighScoresByUser()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            "select h from App\Entity\HighScore h where h.score =
        (select max(h2.score) from App\Entity\HighScore h2 where h2.user = h.user) order by h.score desc"
        );


        return $query->getResult();
    }

    public function findBest(UserInterface $user, int $limit = 15)
    {
        $qb = $this
            ->createQueryBuilder('h')
            ->andWhere('h.user = :user')
            ->setParameter('user', $user)
            ->orderBy('h.score', 'desc')
        ;

        $query = $qb->getQuery();
        $query->setMaxResults($limit);

        return $query->getResult();
    }
}
