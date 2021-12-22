<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $manager->flush();
        
    }
}
