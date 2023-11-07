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
      ['code' => 'm', 'desc' => 'Meter'],
      ['code' => 'cm', 'desc' => 'Centimeter'],
      ['code' => 'mm', 'desc' => 'Millimeter'],
      ['code' => 'ft', 'desc' => 'Feet'],
      ['code' => 'in', 'desc' => 'Inch'],
      ['code' => 'yd', 'desc' => 'Yard'],
      ['code' => 'g', 'desc' => 'Gram'],
      ['code' => 'oz', 'desc' => 'Ounce'],
      ['code' => 'lb', 'desc' => 'Pound'],
      ['code' => 'un', 'desc' => 'Unit'],
      ['code' => 'pkg', 'desc' => 'Package'],
    ];

    foreach ($units_list as $list)
    {
      $unit = new Unit;
      $unit->setUnitCode($list['code']);
      $unit->setUnitDesc($list['desc']);
      $manager->persist($unit);
    }


    $manager->flush();
  }
}
