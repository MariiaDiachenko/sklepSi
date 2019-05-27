<?php

namespace App\Repository;

use App\Entity\Disposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Disposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disposal[]    findAll()
 * @method Disposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisposalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Disposal::class);
    }

    // /**
    //  * @return Disposal[] Returns an array of Disposal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Disposal
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
