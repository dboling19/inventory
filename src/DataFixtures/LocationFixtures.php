<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Transaction;
use App\Entity\ItemLocation;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;

class LocationFixtures extends Fixture
{

  public function __construct(EntityManagerInterface $em, ItemRepository $item_repo, LocationRepository $loc_repo, TransactionRepository $trans_repo, ItemLocationRepository $item_loc_repo)
  {
    $this->em = $em;
    $this->item_repo = $item_repo;
    $this->loc_repo = $loc_repo;
    $this->trans_repo = $trans_repo;
    $this->item_loc_repo = $item_loc_repo;

  }

    
  public function load(ObjectManager $manager): void
  {
    $loc = new Location();
    $loc->setName('Freezer');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setName('Cupboard');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setName('Fridge');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setName('Shelf');
    $this->em->persist($loc);

    $this->em->flush();

  }

}

?>

