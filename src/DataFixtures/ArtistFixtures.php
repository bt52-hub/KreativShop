<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
    public const ARTIST_PROFILE_REF = 'artist-profile';

    public function load(ObjectManager $manager): void
    {
        $artistUser = $this->getReference(UserFixtures::ARTIST_REF, \App\Entity\User::class);
        $artist = new Artist();
        $artist->setUser($artistUser);
        $artist->setPseudo('Beauty Tchéli');
        $artist->setBio('A digital painting artist, passionate about expressive portraiture, with a blend of urban art and classical painting.');
        $manager->persist($artist);
        $manager->flush();

        $this->addReference(self::ARTIST_PROFILE_REF, $artist);
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
