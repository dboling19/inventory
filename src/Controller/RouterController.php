<?php

namespace App\Controller;

use App\Form\RouteType;
use App\Repository\RouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouterController extends AbstractController
{
  public function __construct (
    private RouteRepository $route_repo,
  ) {}


  public function route_list(): Response
  {
    $routes = $this->route_repo->findAll();

    return $this->render('router/route_picker.html.twig', [
      'routes' => $routes,
    ]);
  }
}
