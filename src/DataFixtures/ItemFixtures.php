<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\UnitRepository;
use App\Repository\WarehouseRepository;
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
    private WarehouseRepository $whs_repo,
  ) { }


  public function load(ObjectManager $manager): void
  {
    $min = strtotime((new datetime('+1 week', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));
    $max = strtotime((new datetime('+1 month', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));

    $loc = $this->loc_repo->find('CUP123');
    $whs = $this->whs_repo->find('WHSIN');
    $warm_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'description', 'politics', 'replacement', 'reception', 'discussion', 'driver', 'housing', 'payment', 'employer', 'customer', 'basket', 'cell', 'tongue');
    foreach ($warm_items as $i)
    {
      $item = new Item;
      $item->setItemCode(substr($i, 0,4) . '123');
      $item->setItemDesc($i);
      $item->setItemNotes('warm items test');
      $item->setItemUnit($this->unit_repo->find('oz'));
      $item->addLocation($loc, $whs);
      $this->em->persist($item);
    }

    $loc = $this->loc_repo->find('SHE123');
    $whs = $this->whs_repo->find('WHSIL');
    $warm_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'description', 'politics', 'replacement', 'reception', 'discussion', 'driver', 'housing', 'payment', 'employer', 'customer', 'basket', 'cell', 'tongue');
    foreach ($warm_items as $i)
    {
      $item = $this->item_repo->find(strtoupper(substr($i, 0,4) . '123'));
      $item->addLocation($loc, $whs);
      $this->em->persist($item);
    }

    $loc = $this->loc_repo->find('FRI123');
    $whs = $this->whs_repo->find('WHSS');
    $fridge_items = array('Milk', 'Juice', 'Eggs', 'Cream', 'selection', 'climate', 'variation', 'garbage', 'outcome', 'college', 'diamond', 'guidance', 'arrival', 'mom', 'recipe', 'construction', 'appointment', 'sir', 'leadership', 'blood', 'inspection', 'paper', 'grocery', 'person', 'explanation', 'refrigerator', 'marketing', 'unit', 'perception', 'dirt', 'disaster', 'breath', 'media', 'buyer', 'penalty', 'satisfaction', 'writing', 'people', 'health', 'currency', 'complaint');
    foreach ($fridge_items as $i)
    {
      $item = new Item;
      $item->setItemCode(substr($i, 0,4) . '123');
      $item->setItemDesc($i);
      $item->setItemNotes('fridge test');
      $item->setItemUnit($this->unit_repo->find('oz'));
      $item->addLocation($loc, $whs);
      $this->em->persist($item);
    }

    $loc = $this->loc_repo->find('FRE123');
    $whs = $this->whs_repo->find('WHSOH');
    $freezer_items = array('Ice Cream', 'Ice', 'Chicken', 'Hamburger', 'Steak', 'Cake');
    foreach ($freezer_items as $i)
    {
      $item = new Item;
      $item->setItemCode(substr($i, 0,4) . '123');
      $item->setItemDesc($i);
      $item->setItemNotes('freezer test');
      $item->setItemUnit($this->unit_repo->find('lb'));
      $item->addLocation($loc, $whs);
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
      WarehouseFixtures::class,
    ];
  }
    
}

?>
