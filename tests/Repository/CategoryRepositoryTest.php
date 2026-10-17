<?php

namespace App\Tests\Repository;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryRepositoryTest extends WebTestCase
{
    protected object $categoryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = static::getContainer()->get(CategoryRepository::class);
    }

    public function testGetAllActive(): void
    {
        $this->assertIsArray($this->categoryRepository->getAllActive());
    }

    public function testGetCategoriesQuery(): void
    {
        $paramsCountTotals = [];
        $this->assertTrue($this->categoryRepository->getCategoriesQuery($paramsCountTotals, true) instanceof Query);
        $this->assertTrue($this->categoryRepository->getCategoriesQuery($paramsCountTotals, false) instanceof Query);

        $paramsCountTotals = [
            'total' => true,
            'published' => true,
            'unpublished' => true,
        ];
        $this->assertTrue($this->categoryRepository->getCategoriesQuery($paramsCountTotals, true) instanceof Query);
        $this->assertTrue($this->categoryRepository->getCategoriesQuery($paramsCountTotals, false) instanceof Query);

        $paramsCountTotals = [
            'total' => true,
            'published' => true,
        ];
        $this->assertTrue($this->categoryRepository->getCategoriesQuery($paramsCountTotals, true) instanceof Query);
        $this->assertTrue($this->categoryRepository->getCategoriesQuery($paramsCountTotals, false) instanceof Query);
    }
}
