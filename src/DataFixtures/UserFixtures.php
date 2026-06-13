<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const CUSTOMER_REF = 'user-customer';
    public const ARTIST_REF = 'user-artist';

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $customer = new User();
        $customer->setFirstname('Sashi');
        $customer->setName('Mimi');
        $customer->setEmail('customer@test.com');
        $customer->setPassword($this->hasher->hashPassword($customer, 'password'));
        $customer->setRole(UserRole::Customer);
        $customer->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($customer);

        $artistUser = new User();
        $artistUser->setFirstname('Beauty');
        $artistUser->setName('Tchéli');
        $artistUser->setEmail('artist@test.com');
        $artistUser->setPassword($this->hasher->hashPassword($artistUser, 'password'));
        $artistUser->setRole(UserRole::Artist);
        $artistUser->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($artistUser);

        $manager->flush();

        // Les références permettent aux autres fixtures de récupérer ces objets
        $this->addReference(self::CUSTOMER_REF, $customer);
        $this->addReference(self::ARTIST_REF, $artistUser);
    }
}
