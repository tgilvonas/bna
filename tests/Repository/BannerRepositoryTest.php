<?php

namespace App\Tests\Repository;

use App\Repository\BannerRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BannerRepositoryTest extends WebTestCase
{
    protected object $bannerRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->bannerRepository = static::getContainer()->get(BannerRepository::class);
    }

    public function testGetActiveBanners(): void
    {
        $this->assertIsArray($this->bannerRepository->getActiveBanners());
    }

    public function testGetBannersQuery(): void
    {
        $this->assertTrue($this->bannerRepository->getBannersQuery() instanceof Query);
    }
}
