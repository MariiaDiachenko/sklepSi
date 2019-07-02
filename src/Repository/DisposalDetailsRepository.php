<?php
/**
* App\Repository\DisposalDetailsRepository
*/

namespace App\Repository;

use App\Entity\DisposalDetails;
use App\Repository\DisposalDetailsRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Disposal details repository
 * @method DisposalDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method DisposalDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method DisposalDetails[]    findAll()
 * @method DisposalDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisposalDetailsRepository extends ServiceEntityRepository
{
    /**
    * Construct
    * @param RegistryInterface $registry
    */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DisposalDetails::class);
    }
}
