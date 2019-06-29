<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Product Repository class
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
    * Construct 
    * @param RegistryInterface $registry
    */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * Query all with filters
    * @param int $categoryId
    * @param int $shopId
    *
    * @return QueryBuilder
    */
    public function queryAllWithFilters($categoryId = null, $shopId = null): QueryBuilder
    {
        $builder = $this->createQueryBuilder('p')
        ->orderBy('p.timestamp', 'DESC');

        if ($categoryId && is_int($categoryId)) {
            $builder->where('p.category = :id')
            ->setParameter(':id', $categoryId, \PDO::PARAM_INT);
        }

        if ($shopId && is_int($shopId)) {
            $builder->where('p.shop = :id')
            ->setParameter(':id', $shopId, \PDO::PARAM_INT);
        }

        return $builder;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
