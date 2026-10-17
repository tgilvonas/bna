<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    protected UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setEmail('superadmin@bna.com');
        $admin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_EDITOR', 'ROLE_MARKETING_MANAGER']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'pa55word123'));
        $manager->persist($admin);

        $admin = new Admin();
        $admin->setEmail('editor@bna.com');
        $admin->setRoles(['ROLE_EDITOR']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'pa55word123'));
        $manager->persist($admin);

        $admin = new Admin();
        $admin->setEmail('manager@bna.com');
        $admin->setRoles(['ROLE_MARKETING_MANAGER']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'pa55word123'));
        $manager->persist($admin);

        $manager->flush();
    }
}
