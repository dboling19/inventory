<?php

namespace App\DataFixtures;

use App\Entity\Terms;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TermsFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $terms = new Terms();
    $terms->setTermsCode('D02');
    $terms->setTermsDesc('2% 10, Net 30');
    $terms->setTermsDueDays(30);
    $terms->setTermsDiscDays(10);
    $terms->setTermsDiscPct(2.000);
    $manager->persist($terms);
    $manager->flush();
  }
}
