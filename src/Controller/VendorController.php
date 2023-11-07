<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorController extends AbstractController
{
  #[Route('/vendors/', name: 'list_vendors')]
  public function list_vendors(): Response
  {


    return $this->render('vendor/list_vendors.html.twig', [
      'results' => 'results',
    ]);
  }


  #[Route('/display_vendor/', name: 'vendor_details')]
  public function display_vendor(): Response
  {
    

    return $this->render('vendor/list_vendors.html.twig', [
      'results' => 'results',
    ]);
  }
}
