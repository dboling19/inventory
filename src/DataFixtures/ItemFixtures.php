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
    $min = strtotime((new datetime('+1 week', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));
    $max = strtotime((new datetime('+1 month', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));

    $loc = $this->loc_repo->findOneBy(['name' => 'Cupboard']);
    $cupboard_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    foreach ($cupboard_items as $i)
    {
      $item = new Item;
      $item->setName($i);
      $item->setDescription('cupboard test');
      $item->setExpDate($this->random_date($min, $max));

      $item_loc = new ItemLocation;
      $item_loc->setItem($item);
      $item_loc->setQuantity(mt_rand(1,10));
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
      $item->setExpDate($this->random_date($min, $max));

      $item_loc = new ItemLocation;
      $item_loc->setItem($item);
      $item_loc->setQuantity(mt_rand(1,10));
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
      $item->setExpDate($this->random_date($min, $max));

      $item_loc = new ItemLocation;
      $item_loc->setItem($item);
      $item_loc->setQuantity(mt_rand(1,10));
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
      $item->setExpDate($this->random_date($min, $max));

      $item_loc = new ItemLocation;
      $item_loc->setItem($item);
      $item_loc->setQuantity(mt_rand(1,10));
      $item_loc->setLocation($loc);

      $this->em->persist($item_loc);
      $this->em->flush();
    }
  }

  public function random_date($min, $max)
  {
    // Convert back to desired date format
    return new datetime(date('Y-m-d', mt_rand($min, $max)), new datetimezone('america/indiana/indianapolis'));
  }


  public function getDependencies()
  {
    return [
      LocationFixtures::class,
    ];
  }
    
}

?>
