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

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class LocationController extends AbstractController
{

  private $em;
  private $item_repo;
  private $loc_repo;
  private $trans_repo;
  private $item_loc_repo;
  private $paginator;
  private $date;
  private $request_stack;

  public function __construct(EntityManagerInterface $em, ItemRepository $item_repo, LocationRepository $loc_repo, TransactionRepository $trans_repo, ItemLocationRepository $item_loc_repo, PaginatorInterface $paginator, RequestStack $request_stack)
  {
    $this->em = $em;
    $this->item_repo = $item_repo;
    $this->loc_repo = $loc_repo;
    $this->trans_repo = $trans_repo;
    $this->item_loc_repo = $item_loc_repo;
    $this->paginator = $paginator;
    $this->date = (new \DateTime('now'))->format('Y-m-d');
    $this->request_stack = $request_stack;
  }

  
  /**
   * Function to display all locations in the system
   * 
   * @author Daniel Boling
   */
  #[Route('/locations', name:'locations_display')]
  public function show_locations(Request $request): Response
  {
    $params = [
      's' => $request->query->get('s') ?? false,
    ];

    $loc_result = $this->loc_repo->findAll();
    $loc_result = $this->paginator->paginate($loc_result, $request->query->getInt('page', 1), 40);

    return $this->render('overview_locations.html.twig', [
      'params' => $params,
      'loc_result' => $loc_result,
    ]);
  }


  /**
   * View location details
   * 
   * @author Daniel Boling
   */
  #[Route('/location/{id}', name:'view_location')]
  public function view_location(Request $request, $id): Response
  {
    $loc = $this->loc_repo->find($id);
    $item_loc = $loc->getItemlocation();
    // $items = $this->paginator->paginate($item_loc, $request->query->getInt('page', 1), 1);
    $loc_qty = $this->item_loc_repo->getLocQty($id)[0]['quantity'];
    if ($request->cookies->get('location_items_limit') != null)
    {
      $limit = ['items_limit' => $request->cookies->get('location_items_limit')];
    } else {
      $limit = ['items_limit' => 25];
      $cookie = new Cookie('location_items_limit', $limit['items_limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();  
    }

    $params = [
      'location' => $loc->getId(),
      'name' => $loc->getName(),
      's' => $request->query->get('s') ?? false,
      'limit' => $limit['items_limit'],
      'item_name' => '',
    ];

    if($request->query->all() && !$request->query->get('s'))
    // prevent condition from returning true after name change
    {
      $params = array_merge($params, $request->query->all());
      if (isset($params['limit']) && $limit['items_limit'] !== $params['limit'])
      {
        $cookie = new Cookie('location_items_limit', $params['limit']);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();  
        $limit['items_limit'] = $params['limit'];
      }
      $result = $this->item_loc_repo->findItem($params);
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['items_limit']);
      dd($result);

    } else {
      $result = $this->item_loc_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['items_limit']);

    }

    return $this->render('view_location.html.twig', [
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
    if ($request->query->all() && !$request->query->get('s'))
    {
      $loc = new Location;
      $params = $request->query->all();
      $loc->setName($params['name']);
      $this->em->persist($loc);
      $this->em->flush();
    }
    return $this->redirectToRoute('locations_display', ['s' => true]);
  }


  /**
   * Modifies location details and redirects back to view location
   * 
   * @author Daniel Boling
   */
  #[Route('/modify/location/{id}', name:'modify_location')]
  public function modify_location(Request $request, $id): Response
  {
    if($request->query->all()) {
      $params = $request->query->all();
      $loc = $this->loc_repo->find($id);
      $loc->setName($params['name']);
      $this->em->persist($loc);
      $this->em->flush();
    }
    return $this->redirectToRoute('view_location', ['id' => $id, 's' => true]);
  }


  /**
   * Deletes location if no entites are under it and redirects back to show locations
   * 
   * @author Daniel Boling
   */
  #[Route('/delete/location/{id}', name:'delete_location')]
  public function delete_location(Request $request, $id): Response
  {
    $loc = $this->loc_repo->find($id);
    $loc_qty = $this->item_loc_repo->getLocQty($id)[0]['quantity'];
    if($loc_qty == 0 or $loc_qty == NULL)
    {
      $this->em->remove($loc);
      $this->em->flush();
    }
    return $this->redirectToRoute('show_locations');
  }

}





// EOF

?>
