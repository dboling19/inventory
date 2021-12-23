<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item;
use App\Entity\Location;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $loc = new Location();
        $loc->setName('Freezer');
        $manager->persist($loc);

        $loc = new Location();
        $loc->setName('Cupboard');
        $manager->persist($loc);

        $item = new Item();
        $item->setName('Ketchup');
        $item->setQuantity(2);
        $item->setLocation($loc);
        $manager->persist($item);

        $manager->flush();
    }
    
}
