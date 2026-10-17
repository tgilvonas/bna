<?php

namespace App\Tests\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class GenericControllerTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected string $baseUrl;

    protected string $httpHost;

    protected ?object $adminRepository;

    protected Admin $superAdmin;

    protected Admin $editor;

    protected Admin $marketingManager;

    protected object $translator;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpHost = $_ENV['HTTP_HOST'];
        $this->baseUrl = $_ENV['BASE_URL'];

        $this->client = static::createClient([], ['HTTP_HOST' => $this->httpHost]);

        $this->adminRepository = static::getContainer()->get(AdminRepository::class);

        $this->superAdmin = $this->adminRepository->findOneByEmail('superadmin@bna.com');
        $this->editor = $this->adminRepository->findOneByEmail('editor@bna.com');
        $this->marketingManager = $this->adminRepository->findOneByEmail('manager@bna.com');

        $this->translator = static::getContainer()->get(TranslatorInterface::class);
    }

    protected function translate(string $translationKey, string $domain = 'app'): string
    {
        return $this->translator->trans($translationKey, [], $domain);
    }
}