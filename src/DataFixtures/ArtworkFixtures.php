<?php

namespace App\DataFixtures;

use App\Entity\Artwork;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtworkFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $artist = $this->getReference(ArtistFixtures::ARTIST_PROFILE_REF, \App\Entity\Artist::class);

        $artworksData = [
            ['title' => 'The Drift',              'file' => 'The-Drift.webp'],
            ['title' => 'X-Games Aspen',          'file' => 'X-Games-Aspen.webp'],
            ['title' => 'The Garbage',            'file' => 'The-Garbage.webp'],
            ['title' => 'Fuku Child',             'file' => 'FukuChild.webp'],
            ['title' => 'The Playground',         'file' => 'The-Playground.webp'],
            ['title' => 'The Revolution at Space','file' => 'The-Revolution-at-Space.webp'],
            ['title' => 'The Weeknd',             'file' => 'The-Weeknd.webp'],
        ];

        foreach ($artworksData as $index => $data) {
            $artwork = new Artwork();
            $artwork->setTitle($data['title']);
            $artwork->setFileUrl($data['file']);
            $artwork->setArtist($artist);
            $artwork->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($artwork);
            $this->addReference('artwork-' . $index, $artwork);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ArtistFixtures::class];
    }
}
