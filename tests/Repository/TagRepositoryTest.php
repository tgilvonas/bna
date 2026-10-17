<?php

namespace App\Tests\Repository;

use App\Repository\TagRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagRepositoryTest extends WebTestCase
{
    protected object $tagRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->tagRepository = static::getContainer()->get(TagRepository::class);
    }

    public function testGetTagQuery(): void
    {
        $paramsCountTotals = [];
        $this->assertTrue($this->tagRepository->getTagQuery($paramsCountTotals, true) instanceof Query);

        $paramsCountTotals = [
            'total' => true,
            'published' => true,
            'unpublished' => true,
        ];
        $this->assertTrue($this->tagRepository->getTagQuery($paramsCountTotals, true) instanceof Query);

        $paramsCountTotals = [
            'total' => true,
            'published' => true,
        ];
        $this->assertTrue($this->tagRepository->getTagQuery($paramsCountTotals, true) instanceof Query);
    }
}
