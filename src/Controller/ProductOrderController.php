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

class ProductOrderController extends AbstractController
{
  public function __construct(
    private PurchaseOrderRepository $po_repo,
    private LocationRepository $loc_repo,
    private PaginatorInterface $paginator,
  ) {}

  #[Route('/list_product_orders/', name: 'list_product_orders')]
  public function list_product_orders(Request $request): Response
  {

    $product_orders_limit_cookie = $request->cookies->get('product_orders_limit') ?? 100;
    $params = [
      'item_name' => '',
      'limit' => $product_orders_limit_cookie,
    ];
    $params = array_merge($params, $request->query->all());
    if ($product_orders_limit_cookie !== $params['limit'])
    // if form submitted limit != cookie limit then update the cookie
    {
      $cookie = new Cookie('items_limit', $params['limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $product_orders_limit_cookie = $params['limit'];
    }
    $params = array_merge($params, $request->query->all());
    $result = $this->po_repo->filter($params);
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);
    return $this->render('product_order/list_product_orders.html.twig', [
      'result' => $result,
      'locations' => $this->loc_repo->findAll(),
    ]);
  }
}
