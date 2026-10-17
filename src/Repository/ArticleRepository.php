<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getHomePageArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.category', 'c', 'on a.categories_id = c.id')
            ->andWhere('a.on_first_page = 1')
            ->andWhere('a.published = 1')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getCategoryArticlesQuery(string $slug): Query
    {
        return $this->createQueryBuilder('a')
            ->join('a.category', 'c', 'on a.categories_id = c.id')
            ->addSelect('c')
            ->andWhere('a.published = 1')
            ->andWhere('c.category_url = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('a.date', 'DESC')
            ->getQuery();
    }

    public function getArticlesQuery(array $searchParams): Query
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->join('a.category', 'c', 'on a.categories_id = c.id')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC');

        if (isset($searchParams['search_word']) && !empty($searchParams['search_word'])) {
            $queryBuilder->andWhere('a.article_title LIKE :search_word OR a.intro LIKE :search_word OR a.content LIKE :search_word')
                ->setParameter('search_word', '%' . $searchParams['search_word'] . '%');
        }
        if (isset($searchParams['date_from']) && !empty($searchParams['date_from'])) {
            $queryBuilder->andWhere('a.date >= :date_from')
                ->setParameter('date_from', $searchParams['date_from']);
        }
        if (isset($searchParams['date_to']) && !empty($searchParams['date_to'])) {
            $queryBuilder->andWhere('a.date <= :date_to')
                ->setParameter('date_to', $searchParams['date_to']);
        }
        if (isset($searchParams['category']) && is_numeric($searchParams['category'])) {
            $queryBuilder->andWhere('a.categories_id = :category')
                ->setParameter('category', $searchParams['category']);
        }
        if (isset($searchParams['published']) && is_numeric($searchParams['published'])) {
            $queryBuilder->andWhere('a.published = :published')
                ->setParameter('published', $searchParams['published']);
        }

        return $queryBuilder->getQuery();
    }

    public function getSearchByWordQuery(string $searchWord): Query|null
    {
        if (!empty($searchWord)) {
            return $this->createQueryBuilder('a')
                ->andWhere('a.article_title LIKE :search_word OR a.intro LIKE :search_word OR a.content LIKE :search_word')
                ->setParameter('search_word', '%' . $searchWord . '%')
                ->andWhere('a.published = 1')
                ->orderBy('a.date', 'DESC')
                ->getQuery();
        } else {
            return null;
        }
    }

    public function getArticleBySlug(string $slug): Article|null
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.article_url = :article_slug')
            ->setParameter('article_slug', $slug)
            ->andWhere('a.published = 1')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getSumsOfArticlesByYear(): array
    {
        return $this->createQueryBuilder('a')
            ->select('YEAR(a.date) AS articles_year, COUNT(a.id) AS articles_count')
            ->andWhere('a.published = 1')
            ->orderBy('articles_year', 'ASC')
            ->groupBy('articles_year')
            ->getQuery()
            ->getResult();
    }
}
