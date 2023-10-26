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
      ['name' => 'Warehouse North', 'code' => 'WHSN'],
      ['name' => 'Warehouse South', 'code' => 'WHSS'],
      ['name' => 'Warehouse East', 'code' => 'WHSE'],
      ['name' => 'Warehouse West', 'code' => 'WHSW'],
      ['name' => 'Warehouse IN', 'code' => 'WHSIN'],
      ['name' => 'Warehouse OH', 'code' => 'WHSOH'],
      ['name' => 'Warehouse IL', 'code' => 'WHSIL'],
    ];
    foreach ($warehouses as $warehouse_array)
    {
      $warehouse = new Warehouse();
      $warehouse->setWhsName($warehouse_array['name']);
      $warehouse->setWhsCode($warehouse_array['code']);
      $manager->persist($warehouse);
    }

    $manager->flush();
  }
}
