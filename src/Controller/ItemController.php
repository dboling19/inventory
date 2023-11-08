<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\UnitRepository;
use App\Repository\WarehouseRepository;
use App\Service\TransactionService;
use Knp\Component\Pager\PaginatorInterface;
use Datetime;
use Datetimezone;


class ItemController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em,
    private ItemRepository $item_repo,
    private LocationRepository $loc_repo,
    private TransactionRepository $trans_repo,
    private ItemLocationRepository $item_loc_repo,
    private UnitRepository $unit_repo,
    private WarehouseRepository $whs_repo,
    private TransactionService $trans_service,
    private PaginatorInterface $paginator,
  ) { }

  /**
   * Function to display all items in the system
   * 
   * @author Daniel Boling
   */
  #[Route('/', name:'item_list')]
  public function list_items(Request $request): Response
  {
    $items_limit_cookie = $request->cookies->get('items_limit') ?? 25;
    $entity_type = 'item';
    $params = [
      'limit' => $items_limit_cookie,
      'item_code' => null,
      'item_desc' => null,
      'item_notes' => null,
      'item_exp_date' => null,
      'item_unit' => null,
      'item_total_qty' => null,
      'item_location' => null,
    ];
    if ($items_limit_cookie !== $params['limit'])
    // if form submitted limit != cookie limit then update the cookie
    {
      $cookie = new Cookie('items_limit', $params['limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $items_limit_cookie = $params['limit'];
    }
    // to autofill form fields, or leave them null.
    switch ($request->getMethod())
    {
      case 'POST':
        $params = array_merge($request->request->all(), $params);
        break;
      case 'GET':
        $params = array_merge($request->query->all(), $params);
        break;
    }
    dd($params);
    $result = $this->item_repo->findAll();
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);
    if (!$request->request->get('item_code') && !$request->query->get('item_code')) {
      return $this->render('item/list_items.html.twig', [
        'locations' => $this->loc_repo->findAll(),
        'warehouses' => $this->whs_repo->findAll(),
        'units' => $this->unit_repo->findAll(),
        'result' => $result,
        'params' => $params,
        'entity_type' => $entity_type,
      ]);
    }


    $item = $this->item_repo->find($request->query->get('item_code'));
    $item_locations = [];
    foreach ($item->getItemLoc() as $item_loc)
    {
      $item_locations[] = ['loc_code' => $item_loc->getLocation()->getLocDesc(), 'whs_code' => $item_loc->getWarehouse()->getWhsCode(), 'item_qty' => $item_loc->getItemQty()];
    }
    $params = array_merge($params, [
      'item_code' => $item->getItemCode(),
      'item_desc' => $item->getItemDesc(),
      'item_notes' => $item->getItemNotes(),
      'item_exp_date' => $item->getItemExpDate(),
      'item_total_qty' => $item->getItemQty(),
      'item_unit' => $item->getItemUnit()->getUnitCode(),
      'item_location' => $item_locations,
    ]);

    return $this->render('item/list_items.html.twig', [
      'locations' => $this->loc_repo->findAll(),
      'warehouses' => $this->whs_repo->findAll(),
      'units' => $this->unit_repo->findAll(),
      'result' => $result,
      'params' => $params,
      'entity_type' => $entity_type,
    ]); 
  }


  /**
   * 
   */
  #[Route('/item/search/', name:'item_search')]
  public function item_search(Request $request): Response
  {
    if (!$request->query->get('item_code'))
    {
      return $this->redirectToRoute('item_list');
    }

    return $this->redirectToRoute('item_list', ['item_code' => $request->query->get('item_code')]);
  }


  /**
   * Function to display and handle new item forms
   * 
   * @author Daniel Boling
   */
  #[Route('/item/new/', name:'item_new')]
  public function item_new(Request $request): Response
  {
    if(!$request->request->all()) { return $this->redirectToRoute('item_list'); }

    $params = $request->request->all();
    $item = new Item;
    $item->setItemDesc($params['item_desc']);
    $item->setItemNotes($params['item_notes']);
    $date = new datetime($params['item_exp_date'], new datetimezone('America/Indiana/Indianapolis'));
    $item->setItemExpDate($date);
    $item->setItemUnit($this->unit_repo->find($params['item_unit']));
    foreach (explode(',', $params['item_locations']) as $loc_addr)
    {
      $loc = $this->loc_repo->find($loc_addr['loc_code']); 
      $whs = $this->whs_repo->find($loc_addr['whs_code']);
      $item->addLocation($loc, $whs);
    }
    $this->em->persist($item);
    // $this->trans_service->create_transaction($item, $location);
    $this->em->flush();
    $this->addFlash('success', 'Item Created');
    return $this->redirectToRoute('item_list');
  }

  
  /**
   * Function to display and handle item modification forms
   * 
   * @author Daniel Boling
   */
  #[Route('/item/modify/', name:'item_modify')]
  public function display_item(Request $request): Response
  {
    if(!$request->request->all()) { return $this->redirectToRoute(('item_list')); }
    // no form submission
    // this should never happen, but prevents someone just entering the url.

    $item_code = $request->query->get('item_code');
    $item = $this->item_repo->find($item_code);

    $params = [
      'item_code' => $item->getItemCode(),
      'item_desc' => $item->getItemDesc(),
      'item_notes' => $item->getItemNotes(),
      'item_exp_date' => null,
      'item_qty' => $item->getItemQty(),
    ];
    // item_exp_date is null as it gets set below

    if ($item->getItemExpDate()) { $params['item_exp_date'] = $item->getItemExpDate()->format('Y-m-d'); }
    // setting this here because if the date is null, format draws an error.


    // item modification stage
    $params = $request->request->all();
    $item->setItemDesc($params['item_desc']);
    $item->setItemNotes($params['item_notes']);
    $date = new datetime($params['item_exp_date'], new datetimezone('America/Indiana/Indianapolis'));
    $item->setItemExpDate($date);
    foreach (explode(',', $params['item_locations']) as $loc_addr)
    {
      $loc = $this->loc_repo->find($loc_addr['loc_code']); 
      $whs = $this->whs_repo->find($loc_addr['whs_code']);
      $item->addLocation($loc, $whs);
    }
    $this->em->persist($item);
    // $this->trans_service->create_transaction($item, $location, ((int)trim($params['quantity_change'], '+')));
    $this->em->flush();
    $this->addFlash('success', 'Item Updated');
    return $this->redirectToRoute('item_search', ['item_code' => $item->getItemCode()]);
  }


  /**
   * Delete item only if quantity = 0
   * 
   * @author Daniel Boling
   */
  #[Route('/item/delete/', name:'item_delete')]
  public function delete_item(Request $request)
  {
    $item_code = $request->query->get('item_code');
    $item_loc = $this->item_loc_repo->find($item_code);
    $item = $item_loc->getItem();

    if ($item_loc->getItemQty() !== 0)
    {
      return $this->redirectToRoute('item_search', ['item_code' => $item_code]);
    }

    $this->em->remove($item_loc);
    $this->em->flush();
    $this->addFlash('success', 'Removed Item Entry');
    return $this->redirectToRoute('item_list');
  }


  /**
   * Clear item exp_date from display_item form
   * 
   * @author Daniel Boling
   */
  #[Route('/item/clear_exp_date/', name:'item_clear_exp_date')]
  public function clear_exp_date(Request $request): Response
  {
    if (!request->query->get('item_code'))
    {
      return $this->redirectToRoute('item_list');
    }
    $item_code = $request->query->get('item_code');
    $item = $this->item_repo->find($item_code);
    $item->setExpDate(null);
    $this->em->persist($item);
    $this->em->flush();
    $this->addFlash('success', 'Cleared item expiration date');
    return $this->redirectToRoute('item_search', ['item_code' => $item_code]);
  }

}


// EOF