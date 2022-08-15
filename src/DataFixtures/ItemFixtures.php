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

        $loc = new Location();
        $loc->setName('Top Shelf');
        $manager->persist($loc);

        $loc = new Location();
        $loc->setName('Bottom Shelf');
        $manager->persist($loc);

        $items = array('Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apples', 'Oranges', 'Lemons', 'Limes');
        foreach ($items as $i) {
            $item = new Item();
            $item->setName($i);
            $item->setQuantity(1);
            $item->setLocation($loc);
            $manager->persist($item);
        }

        $manager->flush();
    }
    
}
