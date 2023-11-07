<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Location;


class LocationFixtures extends Fixture
{
  public function __construct(
    private EntityManagerInterface $em,
  ) { }

    
  public function load(ObjectManager $manager): void
  {
    $loc = new Location();
    $loc->setLocDesc('Freezer');
    $loc->setLocCode(substr($loc->getLocDesc(),0,3) . '123');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocDesc('Cupboard');
    $loc->setLocCode(substr($loc->getLocDesc(),0,3) . '123');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocDesc('Fridge');
    $loc->setLocCode(substr($loc->getLocDesc(),0,3) . '123');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocDesc('Shelf');
    $loc->setLocCode(substr($loc->getLocDesc(),0,3) . '123');
    $this->em->persist($loc);

    $this->em->flush();

  }

}

?>

