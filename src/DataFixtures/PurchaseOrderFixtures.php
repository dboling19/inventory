<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\TermsRepository;
use App\Repository\VendorRepository;
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
    private VendorRepository $vendor_repo,
    private TermsRepository $terms_repo,
  ) { }


  public function load(ObjectManager $manager): void
  {
    $min = strtotime((new datetime('+1 week', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));
    $max = strtotime((new datetime('+1 month', new datetimezone('america/indiana/indianapolis')))->format('Y-m-d'));

    $cupboard_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    $shelf_items = array('Coffee', 'Sugar', 'Chocolate', 'Salt', 'Ketchup', 'Cookies', 'Crackers', 'Chips', 'Mustard', 'Candy', 'Apple', 'Orange', 'Lemon', 'Lime');
    $fridge_items = array('Milk', 'Juice', 'Eggs', 'Cream');
    $freezer_items = array('Ice Cream', 'Ice', 'Chicken', 'Hamburger', 'Steak', 'Cake');
    $item_categories = [$cupboard_items, $shelf_items, $fridge_items, $freezer_items];
    foreach ($item_categories as $key=>$item_category)
    {
      $po = new PurchaseOrder;
      $po->setPoOrderDate($this->random_date($min,$max));
      $po->setVendor($this->vendor_repo->find('AMZ1'));
      $po_cost = 0;

      // get location_name for finding item_loc later
      // put here to reduce cycles
      switch ($key)
      {
        case 0:
          $location_name = 'Cupboard';
          break;
        case 1:
          $location_name = 'Shelf';
          break;
        case 2:
          $location_name = 'Fridge';
          break;
        case 3:
          $location_name = 'Freezer';
          break;
      }
      foreach ($item_category as $item_name)
      {
        $item = $this->item_repo->find($item_name);
        $po_line = new PurchaseOrderLine;
        $po_line->setItem($item);
        $po_line->setPoStatus(1);
        $po_line->setQtyOrdered(mt_rand(1,10));
        $po_line->setQtyReceived(mt_rand(1,30));
        $po_line->setQtyRejected($po_line->getQtyOrdered() - $po_line->getQtyReceived());
        $po_line->setPoDueDate(new datetime('+4 weeks', new datetimezone('america/indiana/indianapolis')));
        $po_line->setPoReceivedDate(new datetime('now', new datetimezone('america/indiana/indianapolis')));
        $po_line->setPoReceived(1);
        $po_line->setPoPaid(1);
        $po_line->setItemQuantity($po_line->getQtyReceived());
        $item_cost = mt_rand(10,100)/10;
        $po_line->setItemCost($item_cost);
        $po_cost += $item_cost;

        // Handle receiving items for item quantities
        $item_location = $this->item_loc_repo->findOneBy(['item' => $item->getItemName(), 'location' => $location_name]);
        $item_location->setQuantity($po_line->getQtyReceived());

      }
      $po->setPoPrice($po_cost);
      $po->setPoReceived(1);
      $po->setPoPaid(1);
      $po->setPoFreight(mt_rand(10,100)/10);
      $po->setPoStatus(1);
      $po->setPoShipCode('ASD123');
      $po->setTerms($this->terms_repo->find('D02'));
      $manager->persist($po);
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
      VendorFixtures::class,
    ];
  }
}
