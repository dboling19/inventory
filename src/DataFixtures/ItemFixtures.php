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

  private $em;
  private $item_repo;
  private $loc_repo;
  private $trans_repo;
  private $item_loc_repo;

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

    $loc = $this->loc_repo->findOneBy(['name' => 'Cupboard']);
    $cupboard_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    foreach ($cupboard_items as $i)
    {
      $item = new Item;
      $item_loc = new ItemLocation;
      $item->setName($i);
      $item->setDescription('cupboard test desc');
      $date = new \DateTime('now', new \DateTimeZone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);
      $item_loc->setItem($item);
      $item_loc->setQuantity(1);
      $item_loc->setLocation($loc);
      $this->em->persist($item_loc);
      $this->em->flush();
    }

    $loc = $this->loc_repo->findOneBy(['name' => 'Fridge']);
    $fridge_items = array('Milk', 'Juice', 'Eggs', 'Cream');
    foreach ($fridge_items as $i)
    {
      $item = new Item;
      $item_loc = new ItemLocation;
      $item->setName($i);
      $item->setDescription('fridge test desc');
      $date = new \DateTime('now', new \DateTimeZone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);
      $item_loc->setItem($item);
      $item_loc->setQuantity(1);
      $item_loc->setLocation($loc);
      $this->em->persist($item_loc);
      $this->em->flush();
    }

    $loc = $this->loc_repo->findOneBy(['name' => 'Shelf']);
    $shelf_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    foreach ($shelf_items as $i)
    {
      $item = new Item;
      $item_loc = new ItemLocation;
      $item->setName($i);
      $item->setDescription('shelf test desc');
      $date = new \DateTime('now', new \DateTimeZone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);
      $item_loc->setItem($item);
      $item_loc->setQuantity(1);
      $item_loc->setLocation($loc);
      $this->em->persist($item_loc);
      $this->em->flush();
    }

    $loc = $this->loc_repo->findOneBy(['name' => 'Freezer']);
    $shelf_items = array('Ice Cream', 'Ice', 'Chicken', 'Hamburger', 'Steak', 'Cake', 'Chocolate', 'Juice');
    foreach ($shelf_items as $i)
    {
      $item = new Item;
      $item_loc = new ItemLocation;
      $item->setName($i);
      $item->setDescription('freezer test desc');
      $date = new \DateTime('now', new \DateTimeZone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);
      $item_loc->setItem($item);
      $item_loc->setQuantity(1);
      $item_loc->setLocation($loc);
      $this->em->persist($item_loc);
      $this->em->flush();
    }
  }


  public function getDependencies()
  {
    return [
      LocationFixtures::class,
    ];
  }
    
}

?>
