<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item;
use App\Entity\Location;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $loc = new Location();
        $loc->setName('Freezer');
        $manager->persist($loc);

        $loc = new Location();
        $loc->setName('Fridge');
        $manager->persist($loc);

        $item = new Item();
        $item->setName('Ketchup');
        $item->addLoc($loc);
        $item->setUnit('Bottle');
        $item->setQuantity(2);
        $manager->persist($item);

        $item = new Item();
        $item->setName('Mustard');
        $item->addLoc($loc);
        $item->setUnit('Bottle');
        $item->setQuantity(2);
        $manager->persist($item);

        $manager->flush();
    }
}
