<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAllActive(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.active = 1')
            ->orderBy('c.ord', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCategoriesQuery(array $paramsCountTotals = [], bool $onlyActive = false): Query
    {
        $queryBuilder = $this->createQueryBuilder('c');

        $select = ['c.id', 'c.category_name AS categoryName', 'c.active'];

        if ($paramsCountTotals['total'] ?? false) {
            $select[] = 'COUNT(a.id) as articles_total';
        }
        if ($paramsCountTotals['published'] ?? false) {
            $select[] = 'SUM(CASE WHEN a.published = 1 THEN 1 ELSE 0 END) as articles_published';
        }
        if ($paramsCountTotals['unpublished'] ?? false) {
            $select[] = 'SUM(CASE WHEN a.published = 0 THEN 1 ELSE 0 END) as articles_unpublished';
        }

        $queryBuilder->select($select)
            ->leftJoin('c.articles', 'a');

        if ($onlyActive) {
            $queryBuilder->andWhere('c.active = 1');
        }

        return $queryBuilder->orderBy('c.ord', 'ASC')
            ->groupBy('c.id')
            ->getQuery();
    }
}
