<?php

namespace App\Repository;

use App\Entity\Disposal;
use App\Repository\DisposalRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Disposal Repository class
 * @method Disposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disposal[]    findAll()
 * @method Disposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisposalRepository extends ServiceEntityRepository
{
    /**
    * Class construct
    * @param RegistryInterface $registry
    */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Disposal::class);
    }

    /**
    * Query all
    * @return QueryBuilder
    */
    public function queryAll(): QueryBuilder
    {
        $builder = $this->createQueryBuilder('d');

        return $builder;
    }

    /**
    * Query all for specified userId
    * @return QueryBuilder
    */
    public function queryForUser($userId): QueryBuilder
    {
        $builder = $this->createQueryBuilder('d')
          ->where('d.user = :id')
          ->setParameter(':id', $userId, \PDO::PARAM_INT);

        return $builder;
    }
}
