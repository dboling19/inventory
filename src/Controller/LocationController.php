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
  #[Route('/locations/', name:'list_locations')]
  public function locations_list(Request $request): Response
  {
    $locations_limit_cookie = $request->cookies->get('location_items_limit') ?? 25;


    $params = [
      'limit' => $locations_limit_cookie,
      'loc_code' => '',
      'loc_name' => '',
      'loc_desc' => '',
      'loc_whs' => '',
      'loc_item' => '',
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

    if (!$request->request->get('loc_code')) {
      return $this->render('location/list_locations.html.twig', [
        'result' => $result,
        'params' => $params,
        'warehouses' => $this->whs_repo->findAll(),
        'items' => $this->item_repo->findAll(),
      ]);
    }

    $loc = $this->loc_repo->find($request->request->get('loc_code'));
    $params = array_merge($params, [
      'loc_code' => $loc->getLocCode(),
      'loc_name' => $loc->getLocName(),
      'loc_desc' => $loc->getLocDesc(),
      'loc_whs' => $loc->getWarehouses(),
      'loc_whs' => $loc->getItems(),
    ]);

    return $this->render('location/list_locations.html.twig', [
      'result' => $result,
      'params' => $params,
      'loc' => $loc,
    ]);
  }


  /**
   * Creates location from show_locations page
   * 
   * @author Daniel Boling
   */
  #[Route('/create/location', name:'new_location')]
  public function create_location(Request $request): Response
  {
    if ($request->request->all())
    {
      $params = $request->request->all();
      $loc = new Location;
      $loc->setLocName($params['loc_name']);
      $this->em->persist($loc);
      $this->em->flush();
      $this->addFlash('success', 'Location Added');
    }
    return $this->redirectToRoute('list_locations');
  }


  /**
   * Modifies location details and redirects back to view location
   * 
   * @author Daniel Boling
   */
  #[Route('/modify/location/', name:'modify_location')]
  public function modify_location(Request $request): Response
  {
    $id = $request->request->get('location_id');
    if($request->request->all()) {
      $params = $request->request->all();
      $loc = $this->loc_repo->find($id);
      $loc->setLocName($params['location_name']);
      $this->em->persist($loc);
      $this->em->flush();
      $this->addFlash('success', 'Location name updated.');
    }
    return $this->redirectToRoute('display_location', ['location_id' => $id]);
  }


  /**
   * Deletes location if no entites are under it and redirects back to show locations
   * 
   * @author Daniel Boling
   */
  #[Route('/delete/location/', name:'delete_location')]
  public function delete_location(Request $request): Response
  {
    $id = $request->query->get('location_id');
    $loc = $this->loc_repo->find($id);
    $loc_qty = $this->item_loc_repo->getLocQty($id)[0]['quantity'];
    if($loc_qty == 0 or $loc_qty == NULL)
    {
      $this->em->remove($loc);
      $this->em->flush();
      return $this->redirectToRoute('list_locations');
      $this->addFlash('success', 'Location removed.');
    } else {
      $this->addFlash('error', 'Location cannot be deleted.  Contains items.');
      return $this->redirectToRoute('display_location', ['location_id' => $id]);
    }
  }

}


// EOF