<?php

namespace App\Tests\Repository;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleRepositoryTest extends WebTestCase
{
    protected object $articleRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->articleRepository = static::getContainer()->get(ArticleRepository::class);
    }

    public function testGetHomePageArticles(): void
    {
        $this->assertIsArray($this->articleRepository->getHomePageArticles());
    }

    public function testGetCategoryArticlesQuery(): void
    {
        $this->assertTrue($this->articleRepository->getCategoryArticlesQuery('some-slug') instanceof Query);
    }

    public function testGetArticlesQuery(): void
    {
        $searchParams = [];
        $this->assertTrue($this->articleRepository->getArticlesQuery($searchParams) instanceof Query);

        $searchParams = [
            'search_word' => 'some word',
            'date_from' => '2023-10-02',
            'date_to' => '2023-10-20',
            'category' => 1,
            'published' => 1,
        ];
        $this->assertTrue($this->articleRepository->getArticlesQuery($searchParams) instanceof Query);
    }

    public function testGetSearchByWordQuery(): void
    {
        $this->assertNull($this->articleRepository->getSearchByWordQuery(''));
        $this->assertTrue($this->articleRepository->getSearchByWordQuery('some word') instanceof Query);
    }

    public function testGetArticleBySlug(): void
    {
        $this->assertNull($this->articleRepository->getArticleBySlug('some-slug'));
    }

    //throws deprecation warnings:
    /*
    public function testGetSumsOfArticlesByYear(): void
    {
        $this->assertIsArray($this->articleRepository->getSumsOfArticlesByYear());
    }
    */
}
