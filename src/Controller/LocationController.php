<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Location;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\WarehouseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class LocationController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em,
    private ItemRepository $item_repo,
    private LocationRepository $loc_repo,
    private TransactionRepository $trans_repo,
    private ItemLocationRepository $item_loc_repo,
    private WarehouseRepository $whs_repo,
    private PaginatorInterface $paginator,
    private RequestStack $request_stack
  ) { }

  
  /**
   * Function to display all locations in the system
   * 
   * @author Daniel Boling
   */
  #[Route('/location/list/', name:'loc_list')]
  public function loc_list(Request $request): Response
  {
    $locations_limit_cookie = $request->cookies->get('location_items_limit') ?? 25;
    $entity_type = 'loc';


    $params = [
      'limit' => $locations_limit_cookie,
      'loc_code' => null,
      'loc_desc' => null,
      'loc_notes' => null,
      'item_total_qty' => null,
      'item_locations' => null,

    ];
    if ($locations_limit_cookie !== $params['limit'])
    // if form submitted limit != cookie limit then update the cookie
    {
      $cookie = new Cookie('location_items_limit', $params['limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $locations_limit_cookie = $params['limit'];
    }
    $params = array_merge($params, $request->query->all());
    $result = $this->loc_repo->findAll();
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);

    if (!$request->request->get('loc_code') && !$request->query->get('loc_code')) {
      return $this->render('location/loc_list.html.twig', [
        'result' => $result,
        'params' => $params,
        'warehouses' => $this->whs_repo->findAll(),
        'items' => $this->item_repo->findAll(),
        'entity_type' => $entity_type,
      ]);
    }


    $loc = $this->loc_repo->find($request->query->get('loc_code'));
    $item_locations = [];
    foreach ($loc->getItemLoc() as $item_loc)
    {
      $item_locations[] = ['whs_code' => $item_loc->getWarehouse()->getWhsCode(), 'item_code' => $item_loc->getItem()->getItemCode(), 'item_qty' => $item_loc->getItemQty()];
    }
    $loc = $this->loc_repo->find($request->query->get('loc_code'));
    $params = array_merge($params, [
      'loc_code' => $loc->getLocCode(),
      'loc_desc' => $loc->getLocDesc(),
      'loc_notes' => $loc->getLocNotes(),
      'item_total_qty' => $loc->getItemQty(),
      'item_locations' => $item_locations,
    ]);

    return $this->render('location/loc_list.html.twig', [
      'result' => $result,
      'params' => $params,
      'items' => $this->item_repo->findAll(),
      'warehouses' => $this->whs_repo->findAll(),
      'entity_type' => $entity_type,
    ]);
  }


  /**
   * Search for location using loc_code.
   * Should be the standard route for querying for locations
   * 
   * @author Daniel Boling
   */
  #[Route('/location/search/', name:'loc_search')]
  public function loc_search(Request $request): Response
  {
    if (!$request->query->get('loc_code'))
    {
      return $this->redirectToRoute('loc_list');
    }

    $loc_code = $request->query->get('loc_code');

    return $this->redirectToRoute('loc_list', ['loc_code' => $loc_code]);
  }


  /**
   * Creates location from show_locations page
   * 
   * @author Daniel Boling
   */
  #[Route('/location/new/', name:'loc_new')]
  public function loc_create(Request $request): Response
  {
    if ($request->request->all())
    {
      $params = $request->request->all();
      $loc = new Location;
      $loc->setLocDesc($params['loc_desc']);
      $this->em->persist($loc);
      $this->em->flush();
      $this->addFlash('success', 'Location Added');
    }
    return $this->redirectToRoute('loc_list');
  }


  /**
   * Modifies location details and redirects back to view location
   * 
   * @author Daniel Boling
   */
  #[Route('/location/modify/', name:'loc_modify')]
  public function loc_modify(Request $request): Response
  {
    $loc_code = $request->request->get('loc_code');
    if($request->request->all()) {
      $params = $request->request->all();
      $loc = $this->loc_repo->find($loc_code);
      $loc->setLocDesc($params['loc_desc']);
      $this->em->persist($loc);
      $this->em->flush();
      $this->addFlash('success', 'Location updated.');
    }
    return $this->redirectToRoute('loc_search', ['loc_code' => $loc_code]);
  }


  /**
   * Deletes location if no entites are under it and redirects back to show locations
   * 
   * @author Daniel Boling
   */
  #[Route('/location/delete/', name:'loc_delete')]
  public function loc_delete(Request $request): Response
  {
    $loc_code = $request->query->get('loc_code');
    $loc = $this->loc_repo->find($loc_code);
    $loc_qty = $this->item_loc_repo->getLocQty($loc_code)[0]['quantity'];
    if($loc_qty == 0 or $loc_qty == NULL)
    {
      $this->em->remove($loc);
      $this->em->flush();
      return $this->redirectToRoute('list_locations');
      $this->addFlash('success', 'Location removed.');
    } else {
      $this->addFlash('error', 'Location cannot be deleted.  Contains items.');
      return $this->redirectToRoute('loc_search', ['loc_code' => $loc_code]);
    }
  }

}


// EOF