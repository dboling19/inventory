<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Transaction;
use App\Entity\ItemLocation;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Form\ModifyFormType;
use App\Form\SearchFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Datetime;
use Datetimezone;


class LocationController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em,
    private ItemRepository $item_repo,
    private LocationRepository $loc_repo,
    private TransactionRepository $trans_repo,
    private ItemLocationRepository $item_loc_repo,
    private PaginatorInterface $paginator,
    private RequestStack $request_stack
  ) { }

  
  /**
   * Function to display all locations in the system
   * 
   * @author Daniel Boling
   */
  #[Route('/locations/', name:'locations_display')]
  public function show_locations(Request $request): Response
  {
    $result = $this->loc_repo->findAll();
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), 40);

    return $this->render('location/overview_locations.html.twig', [
      'result' => $result,
    ]);
  }


  /**
   * View location details
   * 
   * @author Daniel Boling
   */
  #[Route('/display_location/', name:'display_location')]
  public function display_location(Request $request): Response
  {
    $id = $request->query->get('location_id');
    $loc = $this->loc_repo->find($id);
    $loc_qty = $this->item_loc_repo->getLocQty($id)[0]['quantity'];
    $limit_cookie = $request->cookies->get('overview_items_limit') ?? 25;

    $params = [
      'location_id' => $loc->getId(),
      'name' => $loc->getName(),
      'limit' => $limit_cookie,
      'item_name' => '',
      'loc_qty' => $loc_qty,
    ];
    $params = array_merge($params, $request->query->all());
    if ($limit_cookie !== $params['limit'])
    // if form submitted limit != cookie limit then update the cookie
    {
      $cookie = new Cookie('overview_items_limit', $params['limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $limit_cookie = $params['limit'];
    }
    // to autofill form fields, or leave them null.
    $params = array_merge($params, $request->query->all());
    $result = $this->item_loc_repo->filter($params);
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);


    return $this->render('location/display_location.html.twig', [
      'result' => $result,
      'params' => $params,
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
      // if ($params['location_name'] == '') { $this->addFlash('error', 'Location name required.'); }
      // if ($request->getSession()->getFlashBag()->has('error')) { $this->redirectToRoute('location_display'); }
      $loc = new Location;
      $loc->setName($params['location_name']);
      $this->em->persist($loc);
      $this->em->flush();
      // $this->addFlash('success', 'Location Added');
    }
    return $this->redirectToRoute('location_display');
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
      $loc->setName($params['location_name']);
      $this->em->persist($loc);
      $this->em->flush();
      // $this->addFlash('success', 'Location name updated.');
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
      // $this->addFlash('success', 'Location removed.');
    } else {
      // $this->addFlash('error', 'Location cannot be deleted.  Contains items.');
      return $this->redirectToRoute('display_location', ['location_id' => $id]);
    }
  }

}


// EOF