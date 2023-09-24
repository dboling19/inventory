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
      ['code' => 'mg', 'name' => 'Milligram'],
      ['code' => 'g', 'name' => 'Gram'],
      ['code' => 'kg', 'name' => 'Kilogram'],
      ['code' => 'oz', 'name' => 'Ounce'],
      ['code' => 'lb', 'name' => 'Pound'],
    ];

    foreach ($units_list as $list)
    {
      $unit = new Unit;
      $unit->setCode($list['code']);
      $unit->setName($list['name']);
      $manager->persist($unit);
    }


    $manager->flush();
  }
}
