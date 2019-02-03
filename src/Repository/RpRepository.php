<?php

namespace App\Repository;

use App\Entity\Rp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rp[]    findAll()
 * @method Rp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RpRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rp::class);
    }

    public function findByUserId($userId)
    {
        $qb = $this->createQueryBuilder('r');
        $qb ->where($qb->expr()->eq('r.appuser', ':id'));
        return $qb->setParameter(':id', $userId)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Rp[] Returns an array of Rp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rp
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
