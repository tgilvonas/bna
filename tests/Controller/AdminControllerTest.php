<?php

namespace App\Tests\Controller;

use App\Repository\AdminRepository;

class AdminControllerTest extends GenericControllerTestCase
{
    protected ?object $adminRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->adminRepository = static::getContainer()->get(AdminRepository::class);
    }

    public function testIfUnauthenticatedUserCanNotReachIndexAction(): void
    {
        $this->client->request('GET', '/admin/admins');
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');
    }

    public function testIfEditorCanNotReachIndexAction(): void
    {
        $this->client->loginUser($this->editor);
        $this->client->request('GET', '/admin/admins');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfMarketingManagerCanNotReachIndexAction(): void
    {
        $this->client->loginUser($this->marketingManager);
        $this->client->request('GET', '/admin/admins');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfSuperAdminCanReachIndexAction(): void
    {
        $this->client->loginUser($this->superAdmin);
        $crawler = $this->client->request('GET', '/admin/admins');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($this->superAdmin->getEmail(), $crawler->text());
    }

    public function testIfUnauthenticatedUserCanNotReachCreateAction(): void
    {
        $this->client->request('GET', '/admin/admins');
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');

        $this->client->request('POST', '/admin/admins');
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');
    }

    public function testIfEditorCanNotReachCreateAction(): void
    {
        $this->client->loginUser($this->editor);
        $this->client->request('GET', '/admin/admins/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', '/admin/admins/create');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfMarketingManagerCanNotReachCreateAction(): void
    {
        $this->client->loginUser($this->marketingManager);
        $this->client->request('GET', '/admin/admins/create');
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', '/admin/admins/create');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfSuperAdminCanCreateUser(): void
    {
        $this->client->followRedirects();

        $this->client->loginUser($this->superAdmin);
        $crawler = $this->client->request('GET', '/admin/admins/create');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('admin_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['admin_form[email]'] = 'jane@bna.com';
        $form['admin_form[password][first]'] = 'pa55word123';
        $form['admin_form[password][second]'] = 'pa55word123';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString('jane@bna.com', $crawler->text());
    }

    public function testIfUnauthenticatedUserCanNotReachUpdateAction(): void
    {
        $admin = $this->adminRepository->findOneByEmail('jane@bna.com');

        $this->client->request('GET', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');

        $this->client->request('POST', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');
    }

    public function testIfEditorCanNotReachUpdateAction(): void
    {
        $admin = $this->adminRepository->findOneByEmail('jane@bna.com');

        $this->client->loginUser($this->editor);

        $this->client->request('GET', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfManagerCanNotReachUpdateAction(): void
    {
        $admin = $this->adminRepository->findOneByEmail('jane@bna.com');

        $this->client->loginUser($this->marketingManager);

        $this->client->request('GET', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfSuperAdminCanUpdateUser(): void
    {
        $this->client->followRedirects();

        $admin = $this->adminRepository->findOneByEmail('jane@bna.com');

        $this->client->loginUser($this->superAdmin);

        $crawler = $this->client->request('GET', '/admin/admins/update/' . $admin->getId());
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('admin_form[submit]');
        $form = $buttonCrawlerNode->form();
        $form['admin_form[email]'] = 'janedoe@bna.com';
        $form['admin_form[password][first]'] = 'pa55word123456';
        $form['admin_form[password][second]'] = 'pa55word123456';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsString('janedoe@bna.com', $crawler->text());
    }

    public function testIfUnauthenticatedUserCanNotDeleteUser(): void
    {
        $admin = $this->adminRepository->findOneByEmail('janedoe@bna.com');

        $this->client->request('GET', '/admin/admins/delete/' . $admin->getId());
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');

        $this->client->request('POST', '/admin/admins/delete/' . $admin->getId());
        $this->assertResponseRedirects($this->baseUrl . '/admin/login');
    }

    public function testIfEditorCanNotDeleteUser(): void
    {
        $admin = $this->adminRepository->findOneByEmail('janedoe@bna.com');

        $this->client->loginUser($this->editor);

        $this->client->request('GET', '/admin/admins/delete/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', '/admin/admins/delete/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfMarketingManagerCanNotDeleteUser(): void
    {
        $admin = $this->adminRepository->findOneByEmail('janedoe@bna.com');

        $this->client->loginUser($this->marketingManager);

        $this->client->request('GET', '/admin/admins/delete/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('POST', '/admin/admins/delete/' . $admin->getId());
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIfSuperAdminCanDeleteUser(): void
    {
        $this->client->followRedirects();

        $admin = $this->adminRepository->findOneByEmail('janedoe@bna.com');

        $this->client->loginUser($this->superAdmin);

        $crawler = $this->client->request('POST', '/admin/admins/delete/' . $admin->getId());
        $this->assertStringNotContainsString('janedoe@bna.com', $crawler->text());
    }
}
