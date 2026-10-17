<?php

namespace App\Repository;

use App\Entity\Banner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Banner>
 *
 * @method Banner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banner[]    findAll()
 * @method Banner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Banner::class);
    }


    /**
     * @return Banner[] Returns an array of Banner objects
     */
    public function getActiveBanners(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.published = 1')
            ->orderBy('b.ord', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getBannersQuery(): Query
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.ord', 'ASC')
            ->getQuery();
    }
}
