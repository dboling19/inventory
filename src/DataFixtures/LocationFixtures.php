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
  public function __construct(
    private EntityManagerInterface $em,
    private ItemRepository $item_repo,
    private LocationRepository $loc_repo,
    private TransactionRepository $trans_repo,
    private ItemLocationRepository $item_loc_repo,
  ) { }

    
  public function load(ObjectManager $manager): void
  {
    $loc = new Location();
    $loc->setLocName('Freezer');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocName('Cupboard');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocName('Fridge');
    $this->em->persist($loc);

    $loc = new Location();
    $loc->setLocName('Shelf');
    $this->em->persist($loc);

    $this->em->flush();

  }

}

?>

