<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Warehouse;


class WarehouseFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $warehouses = [
      ['desc' => 'Warehouse North', 'code' => 'WHSN'],
      ['desc' => 'Warehouse South', 'code' => 'WHSS'],
      ['desc' => 'Warehouse East', 'code' => 'WHSE'],
      ['desc' => 'Warehouse West', 'code' => 'WHSW'],
      ['desc' => 'Warehouse IN', 'code' => 'WHSIN'],
      ['desc' => 'Warehouse OH', 'code' => 'WHSOH'],
      ['desc' => 'Warehouse IL', 'code' => 'WHSIL'],
    ];
    foreach ($warehouses as $warehouse_array)
    {
      $warehouse = new Warehouse();
      $warehouse->setWhsDesc($warehouse_array['desc']);
      $warehouse->setWhsCode($warehouse_array['code']);
      $manager->persist($warehouse);
    }

    $manager->flush();
  }
}
