<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestUserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
       $admin->setEmail('admin@localhost');
        $admin->setName('Admin');               // correspond à name dans RegistrationFormType
        $admin->setLastName('Super');           // correspond à lastName
        $admin->setAddress('1 rue de l’Admin'); // champ address
        $admin->setPhoneNumber('0612345678');   // doit respecter la regex du form
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));

        $manager->persist($admin);
        $manager->flush();
    }
}