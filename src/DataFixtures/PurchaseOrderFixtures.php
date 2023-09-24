<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PurchaseOrder;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use Datetime;
use Datetimezone;


class PurchaseOrderFixtures extends Fixture implements DependentFixtureInterface
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

    $cupboard_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime', 'Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    $shelf_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Chocolate', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    $fridge_items = array('Milk', 'Juice', 'Eggs', 'Cream');
    $freezer_items = array('Ice Cream', 'Ice', 'Chicken', 'Hamburger', 'Steak', 'Cake', 'Chocolate', 'Juice');
    $item_categories = [$cupboard_items, $fridge_items, $shelf_items, $freezer_items];
    foreach ($item_categories as $list)
    {
      foreach ($list as $item_name)
      {
        $item = $this->item_repo->find($item_name);
        $po = new PurchaseOrder;
        $po->setItem($item->getName());
        $po->setQuantity(mt_rand(1,10));
        $po->setPurchaseDate($this->random_date($min,$max));
        $po->setPrice(mt_rand(10,100)/10);
        $manager->persist($po);
      }
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
      ItemFixtures::class,
      UnitFixtures::class,
      LocationFixtures::class,
    ];
  }
}
