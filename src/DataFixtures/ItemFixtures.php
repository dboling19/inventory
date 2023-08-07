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
use Datetime;
use Datetimezone;

class ItemFixtures extends Fixture implements DependentFixtureInterface
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

    $loc = $this->loc_repo->findOneBy(['name' => 'Cupboard']);
    $cupboard_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    foreach ($cupboard_items as $i)
    {
      $item = new Item;
      $item->setName($i);
      $item->setDescription('cupboard test');
      $date = new datetime('now', new datetimezone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);

      $item_loc = new ItemLocation;
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
      $item->setName($i);
      $item->setDescription('fridge test');
      $date = new datetime('now', new datetimezone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);

      $item_loc = new ItemLocation;
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
      $item->setName($i);
      $item->setDescription('shelf test');
      $date = new datetime('now', new datetimezone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);

      $item_loc = new ItemLocation;
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
      $item->setName($i);
      $item->setDescription('freezer test');
      $date = new datetime('now', new datetimezone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);

      $item_loc = new ItemLocation;
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
