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
    private TransactionService $trans_service,
    private PaginatorInterface $paginator,
  ) { }

  /**
   * Function to display all items in the system
   * 
   * @author Daniel Boling
   */
  #[Route('/', name:'list_items')]
  public function list_items(Request $request): Response
  {
    // setup page display
    // $params['submitted'] = $request->query->get('s') ?? false;
    $items_limit_cookie = $request->cookies->get('items_limit') ?? 25;
    $params = [
      'limit' => $items_limit_cookie,
      'item_id' => '',
      'item_name' => '',
      'item_desc' => '',
      'item_exp_date' => null,
      'item_unit' => '',
      'item_quantity' => '',
      'item_location' => '',
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
    $params = array_merge($params, $request->query->all());
    $result = $this->item_repo->findAll();
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);
    if (!$request->request->get('item_code', 'item_name')) {
      return $this->render('item/list_items.html.twig', [
        'locations' => $this->loc_repo->findAll(),
        'units' => $this->unit_repo->findAll(),
        'result' => $result,
        'params' => $params,
      ]);
    }

    $item = $this->item_repo->find($request->request->get('item_name'));
    $item_locations = [];
    foreach ($item->getLocations() as $location)
    {
      $item_locations[] = $location->getLocName();
    }
    $params = array_merge($params, [
      'item_name' => $item->getItemName(),
      'item_desc' => $item->getItemDesc(),
      'item_exp_date' => $item->getItemExpDate(),
      'item_quantity' => $item->getItemQuantity(),
      'item_unit' => $item->getItemUnit()->getUnitCode(),
      'item_location' => $item_locations,
    ]);

    return $this->render('item/list_items.html.twig', [
      'locations' => $this->loc_repo->findAll(),
      'units' => $this->unit_repo->findAll(),
      'result' => $result,
      'params' => $params,
    ]); 
  }


  /**
   * Function to display and handle new item forms
   * 
   * @author Daniel Boling
   */
  #[Route('/new/item/', name:'new_item')]
  public function new_item(Request $request): Response
  {
    
    if(!$request->request->all()) { return $this->redirectToRoute('list_items'); }

    $params = $request->request->all();
    $item = new Item;
    $item->setItemName($params['item_name']);
    $item->setItemDesc($params['item_desc']);
    $date = new datetime($params['item_exp_date'], new datetimezone('America/Indiana/Indianapolis'));
    $item->setItemExpDate($date);
    $unit = $this->unit_repo->find($params['item_unit']);
    $item->setItemUnit($unit);
    foreach (explode(',', $params['item_locations']) as $location_name)
    {
      $location = $this->loc_repo->find($location_name); 
      $item->addLocation($location);
    }
    $this->em->persist($item);
    // $this->trans_service->create_transaction($item, $location);
    $this->em->flush();
    $this->addFlash('success', 'Item Created');
    return $this->redirectToRoute('list_items', ['s' => true]);
  }

  
  /**
   * Function to display and handle item modification forms
   * 
   * @author Daniel Boling
   */
  #[Route('/modify/item/', name:'modify_item')]
  public function display_item(Request $request): Response
  {
    $id = $request->query->get('item_id');
    $item_loc = $this->item_loc_repo->find($id);
    $item = $item_loc->getItem();

    $params = [
      'item_name' => $item->getItemName(),
      'item_desc' => $item->getItemDesc(),
      'item_exp_date' => null,
      'item_quantity' => $item->getItemQuantity(),
      'item_location' => $item->getLocations(),
    ];

    if ($item->getItemExpDate()) { $params['item_exp_date'] = $item->getItemExpDate()->format('Y-m-d'); }
    
    if(!$request->request->all()) { return $this->redirectToRoute(('list_items')); }
    // no form submission

    // item modification stage
    $params = $request->request->all();
    $item->setItemName($params['item_name']);
    $item->setItemDesc($params['item_desc']);
    $date = new datetime($params['item_exp_date'], new datetimezone('America/Indiana/Indianapolis'));
    $item->setItemExpDate($date);
    $item_loc->setItem($item);
    $item_loc->setQuantity($item_loc->getQuantity() + ((int)trim($params['quantity_change'], '+')));
    $location = $this->loc_repo->find($params['item_location']);
    $item_loc->setLocation($location);
    $this->em->persist($item_loc);
    // $this->trans_service->create_transaction($item, $location, ((int)trim($params['quantity_change'], '+')));
    $this->em->flush();
    $this->addFlash('success', 'Item Updated');
    return $this->redirectToRoute('list_items', ['item_name' => $item->getItemName()]);
  }


  /**
   * Delete item only if quantity = 0
   * 
   * @author Daniel Boling
   */
  #[Route('/delete_item/', name:'delete_item')]
  public function delete_item(Request $request)
  {
    $id = $request->query->get('item_id');
    $item_loc = $this->item_loc_repo->find($id);
    $item = $item_loc->getItem();

    if ($item_loc->getQuantity() == 0)
    {
      $this->em->remove($item_loc);
      $this->em->flush();
      $this->addFlash('success', 'Removed Item Entry');
      return $this->redirectToRoute('list_items');
    } else {
      return $this->redirectToRoute('display_item', ['item_id' => $id]);
    }
  }


  /**
   * Clear item exp_date from display_item form
   * 
   * @author Daniel Boling
   */
  #[Route('/clear_exp_date/', name:'clear_exp_date')]
  public function clear_exp_date(Request $request): Response
  {
    $id = $request->query->get('item_id');
    $item_loc = $this->item_loc_repo->find($id);
    $item = $item_loc->getItem();
    $item->setExpDate(null);
    $this->em->persist($item);
    $this->em->flush();
    $this->addFlash('success', 'Cleared item expiration date');
    return $this->redirectToRoute('display_item', ['item_id' => $id]);
  }

}


// EOF