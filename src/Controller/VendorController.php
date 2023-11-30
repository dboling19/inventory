<?php

namespace App\Controller;

use App\Repository\PurchaseOrderRepository;
use App\Repository\VendorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorController extends AbstractController
{
  public function __construct(
    private PurchaseOrderRepository $po_repo,
    private VendorRepository $vendor_repo,
    private PaginatorInterface $paginator,
  )
  { }
  #[Route('/vendor/list/', name: 'vendor_list')]
  public function vendor_list(): Response
  {


    return $this->render('vendor/list_vendors.html.twig', [
      'results' => 'results',
    ]);
  }


  #[Route('/vendor/details/', name: 'vendor_details')]
  public function vendor_details(Request $request): Response
  {
    if (!$request->query->get('vendor_code')) { return $this->redirectToRoute('vendor_list'); }
    $entity_type = 'vendor';

    $params = [
      'vendor_code' => '',
      'vendor_desc' => '',
      'vendor_notes' => '',
      'vendor_addr' => '',
      'vendor_email' => '',
    ];

    $vendor_code = $this->vendor_repo->find($request->query->get('vendor_code'));
    $result = $this->vendor_repo->find($vendor_code);
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), 100);


    return $this->render('vendor/vendor_details.html.twig', [
      'result' => $result,
      'params' => $params,
      'pos' => $this->po_repo->findAll(),
      'entity_type' => $entity_type,
    ]);
  }
}
