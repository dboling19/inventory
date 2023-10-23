<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\PurchaseOrderRepository;
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
    private LocationRepository $loc_repo,
    private PaginatorInterface $paginator,
  ) {}

  #[Route('/puchase_order/', name: 'list_purchase_orders')]
  public function list_purchase_orders(Request $request): Response
  {
    $purchase_orders_limit_cookie = $request->cookies->get('purchase_orders_limit') ?? 100;
    $params = [
      'item_name' => '',
      'limit' => $purchase_orders_limit_cookie,
    ];
    $params = array_merge($params, $request->query->all());
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
    return $this->render('purchase_order/list_purchase_orders.html.twig', [
      'result' => $result,
    ]);
  }

  #[Route('/purchase_order/display/', name:'display_purchase_order')]
  public function display_purchase_order(Request $request): Response
  {
    

    return $this->render('purchase_order/display_purchase_order.html.twig', [
      'po' => $result,
    ]);
  }
}
