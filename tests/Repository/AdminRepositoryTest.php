<?php

namespace App\Tests\Repository;

use App\Repository\AdminRepository;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminRepositoryTest extends WebTestCase
{
    public function testGetAdminsQuery(): void
    {
        $adminRepository = static::getContainer()->get(AdminRepository::class);

        $this->assertTrue($adminRepository->getAdminsQuery() instanceof Query);
    }
}
