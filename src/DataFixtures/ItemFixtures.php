<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
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

class ItemFixtures extends Fixture implements DependentFixtureInterface
{

  public function __construct(EntityManagerInterface $em, ItemRepository $item_repo, LocationRepository $loc_repo, TransactionRepository $trans_repo, ItemLocationRepository $item_loc_repo)
  {
    $this->em = $em;
    $this->item_repo = $item_repo;
    $this->loc_repo = $loc_repo;
    $this->trans_repo = $trans_repo;
    $this->item_loc_repo = $item_loc_repo;

    $this->date = new \DateTime();

  }


  public function load(ObjectManager $manager): void
  {

    $loc = $this->loc_repo->findOneBy(['name' => 'Cupboard']);

    $cupboard_items = array('Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apples', 'Oranges', 'Lemons', 'Limes');
    foreach ($cupboard_items as $i) {
      $item_loc = new ItemLocation();
      $item_loc->setupItem($i, '', 1, '1', $loc);
      // setupItem(Item[], quantity, change str, Location[])
      $this->em->persist($item_loc);

    }

    $loc = $this->loc_repo->findOneBy(['name' => 'Fridge']);

    $fridge_items = array('Milk', 'Juice', 'Eggs', 'Cream');
    foreach ($fridge_items as $i) {
      $item_loc = new ItemLocation();
      $item_loc->setupItem($i, '', 1, '1', $loc);
      $this->em->persist($item_loc);

    }

    $loc = $this->loc_repo->findOneBy(['name' => 'Shelf']);

    $shelf_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt');
    foreach ($shelf_items as $i) {
      $item_loc = new ItemLocation();
      $item_loc->setupItem($i, '', 1, '1', $loc);
      $this->em->persist($item_loc);

    }

    $loc = $this->loc_repo->findOneBy(['name' => 'Freezer']);

    $shelf_items = array('Ice Cream', 'Ice', 'Chicken');
    foreach ($shelf_items as $i) {
      $item_loc = new ItemLocation();
      $item_loc->setupItem($i, '', 1, '1', $loc);
      $this->em->persist($item_loc);
    }

    $item_loc = new ItemLocation();
    $item_loc->setupItem('Beef', '', 1, '1', $loc);
    $this->em->persist($item_loc);

    $this->em->flush();

  }


  public function getDependencies()
  {
    return [
      LocationFixtures::class,
    ];
  }
    
}

?>
