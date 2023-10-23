<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vendor;

class VendorFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $vendor = new Vendor();
    $vendor->setVendorNum('AMZ1');
    $vendor->setVendorName('Amazon');
    $vendor->setVendorEmail('example@domain.com');
    $vendor->setVendorAddress('123 Sample Street');
    $vendor->setVendorPhone('(555)-123-4567');

    $manager->persist($vendor);
    $manager->flush();
  }
}
