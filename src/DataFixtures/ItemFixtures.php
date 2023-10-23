<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use App\Entity\ItemLocation;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\UnitRepository;
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
    private UnitRepository $unit_repo,
  ) { }


  public function load(ObjectManager $manager): void
  {
    $min = strtotime((new datetime('+1 week', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));
    $max = strtotime((new datetime('+1 month', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));

    $loc_cupboard = $this->loc_repo->find('Cupboard');
    $loc_shelf = $this->loc_repo->find('Shelf');
    $warm_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    foreach ($warm_items as $i)
    {
      $item = new Item;
      $item->setItemName($i);
      $item->setItemDesc('cupboard test');
      $item->setItemUnit($this->unit_repo->find('oz'));
      $item->addLocation($loc_cupboard);
      $item->addLocation($loc_shelf);
      $this->em->persist($item);
    }

    $loc = $this->loc_repo->find('Fridge');
    $fridge_items = array('Milk', 'Juice', 'Eggs', 'Cream');
    foreach ($fridge_items as $i)
    {
      $item = new Item;
      $item->setItemName($i);
      $item->setItemDesc('fridge test');
      $item->setItemUnit($this->unit_repo->find('oz'));
      $item->addLocation($loc);
      $this->em->persist($item);
    }

    $loc = $this->loc_repo->find('Freezer');
    $freezer_items = array('Ice Cream', 'Ice', 'Chicken', 'Hamburger', 'Steak', 'Cake');
    foreach ($freezer_items as $i)
    {
      $item = new Item;
      $item->setItemName($i);
      $item->setItemDesc('freezer test');
      $item->setItemUnit($this->unit_repo->find('oz'));
      $item->addLocation($loc);
      $this->em->persist($item);
    }
    $manager->flush();
  }

  public function random_date($min, $max)
  {
    // Convert back to desired date format
    return new datetime(date('Y-m-d', mt_rand($min, $max)), new datetimezone('america/indiana/indianapolis'));
  }


  public function getDependencies()
  {
    return [
      UnitFixtures::class,
      LocationFixtures::class,
    ];
  }
    
}

?>
