<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Unit;
;

class UnitFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $units_list = [
      ['code' => 'm', 'name' => 'Meter'],
      ['code' => 'cm', 'name' => 'Centimeter'],
      ['code' => 'mm', 'name' => 'Millimeter'],
      ['code' => 'ft', 'name' => 'Feet'],
      ['code' => 'in', 'name' => 'Inch'],
      ['code' => 'yd', 'name' => 'Yard'],
      ['code' => 'g', 'name' => 'Gram'],
      ['code' => 'oz', 'name' => 'Ounce'],
      ['code' => 'lb', 'name' => 'Pound'],
      ['code' => 'un', 'name' => 'Unit'],
      ['code' => 'pkg', 'name' => 'Package'],
    ];

    foreach ($units_list as $list)
    {
      $unit = new Unit;
      $unit->setUnitCode($list['code']);
      $unit->setUnitName($list['name']);
      $manager->persist($unit);
    }


    $manager->flush();
  }
}
