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
    $loc->setLocName('Freezer');
    $loc->setLocCode(substr($loc->getLocName(),0,3) . '123');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocName('Cupboard');
    $loc->setLocCode(substr($loc->getLocName(),0,3) . '123');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocName('Fridge');
    $loc->setLocCode(substr($loc->getLocName(),0,3) . '123');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocName('Shelf');
    $loc->setLocCode(substr($loc->getLocName(),0,3) . '123');
    $this->em->persist($loc);

    $this->em->flush();

  }

}

?>

