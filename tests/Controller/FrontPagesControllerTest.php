<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontPagesControllerTest extends WebTestCase
{
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testSearch(): void
    {
        $client = static::createClient();
        $client->request('GET', '/paieska?search=seimo');
        $this->assertResponseIsSuccessful();
    }
}
