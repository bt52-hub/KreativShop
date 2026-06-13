<?php

namespace App\DataFixtures;

use App\Entity\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TemplateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $templatesData = [
            // artwork-0 = The Drift
            ['artwork' => 'artwork-0', 'name' => 'Square',    'base' => 100,  'w' => 60,  'h' => 60],
            ['artwork' => 'artwork-0', 'name' => 'Square',    'base' => 100, 'w' => 130, 'h' => 130],
            // artwork-1 = The X-games
            ['artwork' => 'artwork-1', 'name' => 'Portrait',  'base' => 100,  'w' => 60,  'h' => 90],
            ['artwork' => 'artwork-1', 'name' => 'Portrait',  'base' => 129.90, 'w' => 90,  'h' => 130],
            // artwork-2 = Garbage
            ['artwork' => 'artwork-2', 'name' => 'Portrait',  'base' => 100,  'w' => 60,  'h' => 90],
            ['artwork' => 'artwork-2', 'name' => 'Portrait',  'base' => 129.90, 'w' => 90,  'h' => 130],
        ];

        foreach ($templatesData as $index => $data) {
            $template = new Template();
            $template->setArtwork($this->getReference($data['artwork'], \App\Entity\Artwork::class));
            $template->setName($data['name']);
            $template->setBasePrice($data['base']);
            $template->setWidth($data['w']);
            $template->setHeight($data['h']);
            $manager->persist($template);
            $this->addReference('template-' . $index, $template);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ArtworkFixtures::class];
    }
}
