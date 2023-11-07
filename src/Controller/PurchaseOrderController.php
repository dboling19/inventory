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

  #[Route('/puchase_orders/', name: 'list_pos')]
  public function list_pos(Request $request): Response
  {
    $purchase_orders_limit_cookie = $request->cookies->get('purchase_orders_limit') ?? 100;
    $params = [
      'limit' => $purchase_orders_limit_cookie,
      'po_num' => '',
      'po_vendor' => '',
      'po_terms' => '',
      'po_total_cost' => '',
      'po_date' => null,
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
    $params = array_merge($params, $request->query->all());

    $result = $this->po_repo->findAll();
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);
    if (!$request->request->get('po_num'))
    {
      return $this->render('purchase_order/list_pos.html.twig', [
        'result' => $result,
        'params' => $params,
        'vendors' => $this->vendor_repo->findAll(),
        'terms' => $this->terms_repo->findAll(),
      ]);    
    }

    $po = $this->po_repo->find($request->request->get('po_num'));
    $params = array_merge($params, [
      'limit' => $purchase_orders_limit_cookie,
      'po_num' => $po->getPONum(),
      'po_vendor' => $po->getPoVendor()->getVendorCode(),
      'po_terms' => $po->getPoTerms()->getTermsCode(),
      'po_total_cost' => $po->getPoTotalCost(),
      'po_date' => $po->getPoOrderDate(),
    ]);

    return $this->render('purchase_order/list_pos.html.twig', [
      'result' => $result,
      'params' => $params,
      'vendors' => $this->vendor_repo->findAll(),
      'terms' => $this->terms_repo->findAll(),
    ]);
  }

  
  #[Route('/purchase_order/details/', name:'po_details')]
  public function po_details(Request $request): Response
  {
    // if (!$request->query->get('po_num'))
    // {
    //   $po_num = '';
    //   $po = null;
    //   $result = null;
    // } else {
    //   $po_num = $request->query->get('po_num');
    //   $po = $this->po_repo->find($po_num);
    //   $result = $this->po_line_repo->findBy(['po' => $po_num]);
    // }

    $params = [
      'po_num' => '',
      'po_vendor' => '',
      'po_line_num' => '',
      
    ];

    $result = $this->po_line_repo->findBy(['po' => 'po_num']);


    return $this->render('purchase_order/po_details.html.twig', [
      'result' => $result,
      'params' => $params,
      'pos' => $this->po_repo->findAll(),
    ]);
  }


  #[Route('/purchase_order/new/', name:'new_po')]
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

    return $this->render('purchase_order/list_pos.html.twig', [
      'po' => $po,
    ]);
  }
}
