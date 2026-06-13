<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Enum\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
            ['template' => 'template-0', 'type' => ProductType::Dibond, 'price' => 150,  'desc' => 'Printed on Dibond, metallic finish.'],
            ['template' => 'template-0', 'type' => ProductType::Plexi,  'price' => 200, 'desc' => 'Printed under plexiglass, glossy effect.'],
            ['template' => 'template-1', 'type' => ProductType::Dibond, 'price' => 150, 'desc' => 'Printed on Dibond, metallic finish.'],
            ['template' => 'template-1', 'type' => ProductType::Plexi,  'price' => 200, 'desc' => 'Printed under plexiglass, glossy effect.'],
            ['template' => 'template-2', 'type' => ProductType::Dibond, 'price' => 150,  'desc' => 'Printed on Dibond, metallic finish.'],
            ['template' => 'template-2', 'type' => ProductType::Plexi,  'price' => 200, 'desc' => 'Printed under plexiglass, glossy effect.'],
            ['template' => 'template-3', 'type' => ProductType::Dibond, 'price' => 150,  'desc' => 'Printed on Dibond, metallic finish.'],
            ['template' => 'template-3', 'type' => ProductType::Plexi,  'price' => 200, 'desc' => 'Printed under plexiglass, glossy effect.'],
            ['template' => 'template-4', 'type' => ProductType::Dibond, 'price' => 150,  'desc' => 'Printed on Dibond, metallic finish.'],
            ['template' => 'template-4', 'type' => ProductType::Plexi,  'price' => 200, 'desc' => 'Printed under plexiglass, glossy effect.'],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setTemplate($this->getReference($data['template'], \App\Entity\Template::class));
            $product->setType($data['type']);
            $product->setFinalPrice($data['price']);
            $product->setDescription($data['desc']);
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TemplateFixtures::class];
    }
}
