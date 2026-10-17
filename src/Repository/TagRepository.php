<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getTagQuery(array $paramsCountTotals = []): Query
    {
        $queryBuilder = $this->createQueryBuilder('t');

        $select = ['t.id', 't.tag'];

        if ($paramsCountTotals['total'] ?? false) {
            $select[] = 'COUNT(a.id) as articles_total';
        }
        if ($paramsCountTotals['published'] ?? false) {
            $select[] = 'SUM(CASE WHEN a.published = 1 THEN 1 ELSE 0 END) as articles_published';
        }
        if ($paramsCountTotals['unpublished'] ?? false) {
            $select[] = 'SUM(CASE WHEN a.published = 0 THEN 1 ELSE 0 END) as articles_unpublished';
        }

        return $queryBuilder->select($select)
            ->leftJoin('t.articles', 'a')
            ->orderBy('t.tag', 'ASC')
            ->groupBy('t.id')
            ->getQuery();
    }
}
