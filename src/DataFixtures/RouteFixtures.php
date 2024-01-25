<?php

namespace App\DataFixtures;

use App\Entity\Route;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RouteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $routes = ['item_list' => ['route' => 'item_list', 'name' => 'Items', 'notes' => 'List and Details of Items'], 'loc_list' => ['route' => 'loc_list', 'name' => 'Locations', 'notes' => 'List and Details of Locations']];
      foreach ($routes as $route)
      {
        $entity = new Route();
        $entity->setRoute($route['route']);
        $entity->setName($route['name']);
        $entity->setNotes($route['notes']);
        $manager->persist($entity);
      }        
      $manager->flush();
    }
}
