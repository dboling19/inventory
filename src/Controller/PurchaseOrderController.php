<?php

namespace App\Controller;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;
use App\Repository\LocationRepository;
use App\Repository\PurchaseOrderRepository;
use App\Repository\PurchaseOrderLineRepository;
use App\Repository\TermsRepository;
use App\Repository\VendorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PurchaseOrderController extends AbstractController
{
  public function __construct(
    private PurchaseOrderRepository $po_repo,
    private PurchaseOrderLineRepository $po_line_repo,
    private LocationRepository $loc_repo,
    private VendorRepository $vendor_repo,
    private TermsRepository $terms_repo,
    private PaginatorInterface $paginator,
  ) {}

  #[Route('/puchase_order/list', name: 'po_list')]
  public function po_list(Request $request): Response
  {
    $purchase_orders_limit_cookie = $request->cookies->get('purchase_orders_limit') ?? 100;
    $entity_type = 'po';
    $params = [
      'limit' => $purchase_orders_limit_cookie,
      'po_num' => '',
      'po_vendor' => '',
      'po_terms' => '',
      'po_total_cost' => '',
      'po_date' => null,
      'vendor_code' => '',
      'vendor_desc' => '',
      'vendor_notes' => '',
      'vendor_addr' => '',
      'vendor_email' => '',
    ];
    if ($purchase_orders_limit_cookie !== $params['limit'])
    // if form submitted limit != cookie limit then update the cookie
    {
      $cookie = new Cookie('items_limit', $params['limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $purchase_orders_limit_cookie = $params['limit'];
    }
    $po_num = $request->query->get('po_num');

    $po_result = $this->po_repo->findAll();
    $po_result = $this->paginator->paginate($po_result, $request->query->getInt('po_page', 1), 100);
    $po_line_result = $this->po_line_repo->findBy(['po' => $po_num]);
    $po_line_result = $this->paginator->paginate($po_line_result, $request->query->getInt('po_line_page', 1), 100);

    if (!$request->query->get('po_num'))
    {
      return $this->render('purchase_order/po_list.html.twig', [
        'po_result' => $po_result,
        'po_line_result' => $po_line_result,
        'params' => $params,
        'vendors' => $this->vendor_repo->findAll(),
        'terms' => $this->terms_repo->findAll(),
        'entity_type' => $entity_type,
      ]);    
    }

    $po = $this->po_repo->find($po_num);
    $po_line_result = $this->po_line_repo->findBy(['po' => $po_num]);
    $po_line_result = $this->paginator->paginate($po_line_result, $request->query->getInt('po_line_page', 1), 100);
    $vendor = $po->getPoVendor();
    $params = array_merge($params, [
      'limit' => $purchase_orders_limit_cookie,
      'po_num' => $po->getPoNum(),
      'po_vendor' => $po->getPoVendor()->getVendorCode(),
      'po_terms' => $po->getPoTerms()->getTermsCode(),
      'po_total_cost' => $po->getPoTotalCost(),
      'po_date' => $po->getPoOrderDate(),
      'vendor_code' => $vendor->getVendorCode(),
      'vendor_desc' => $vendor->getVendorDesc(),
      'vendor_notes' => $vendor->getVendorNotes(),
      'vendor_addr' => $vendor->getVendorAddr(),
      'vendor_email' => $vendor->getVendorEmail(),
    ]);

    return $this->render('purchase_order/po_list.html.twig', [
      'po_result' => $po_result,
      'po_line_result' => $po_line_result,
      'params' => $params,
      'vendors' => $this->vendor_repo->findAll(),
      'terms' => $this->terms_repo->findAll(),
      'entity_type' => $entity_type,
    ]);
  }


  #[Route('/purchase_order/search/', 'po_search')]
  public function po_search(Request $request): Response
  {
    if (!$request->query->get('po_num'))
    {
      return $this->redirectToRoute('po_list');
    }

    $po_num = $request->query->get('po_num');

    return $this->redirectToRoute('po_list', ['po_num' => $po_num]);
  }

  
  #[Route('/purchase_order/details/', name:'po_details')]
  public function po_details(Request $request): Response
  {
    $entity_type = 'po';

    $params = [
      'po_num' => '',
      'po_vendor' => '',
      'po_line_num' => '',
      'po_total_cost' => '',
    ];

    $result = $this->po_line_repo->findBy(['po' => 'po_num']);
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), 100);


    return $this->render('purchase_order/po_details.html.twig', [
      'result' => $result,
      'params' => $params,
      'pos' => $this->po_repo->findAll(),
      'entity_type' => $entity_type,
    ]);
  }


  #[Route('/purchase_order/new/', name:'po_new')]
  public function new_po(Request $request): Response
  {
    if (!$request->query->get('po_num'))
    {
      $po_num = '';
      $po = new PurchaseOrder;
    } else {
      $po_num = $request->query->get('po_num');
      $po = $this->po_repo->find($po_num);
    }

    return $this->redirectToRoute('po_search', ['po_num' => $po_num]);
  }


  #[Route('/purchase_order/delete/', 'po_delete')]
  public function po_delete(): Response
  {
    return $this->redirectToRoute('po_list');
  }


  #[Route('/purchase_order/modify/', 'po_modify')]
  public function po_modify(Request $requst): Response
  {
    return $this->redirectToRoute('po_list');
  }

}
