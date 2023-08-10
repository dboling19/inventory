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
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
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
    private PaginatorInterface $paginator,
  ) { }

  /**
   * Function to display all items in the system
   * 
   * @author Daniel Boling
   */
  #[Route('/', name:'items_display')]
  public function show_items(Request $request): Response
  {
    $limit_cookie = $request->cookies->get('overview_items_limit') ?? 25;
    $params = [
      'item_name' => '',
      'limit' => $limit_cookie,
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

    return $this->render('item/overview_items.html.twig', [
      'locations' => $this->loc_repo->findAll(),
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
    $params['submitted'] = $request->query->get('s') ?? false;
    $locations = $this->loc_repo->findAll();
    
    if(!$request->request->all())
    {
      return $this->render('item/new_item.html.twig', [
        'params' => $params,
        'locations' => $locations,
      ]);
    }

    $params = $request->request->all();
    $item = new Item;
    $item_loc = new ItemLocation;
    $item->setName($params['item_name']);
    $item->setDescription($params['item_desc']);
    $date = new datetime($params['item_exp_date'], new datetimezone('America/Indiana/Indianapolis'));
    $item->setExpDate($date);
    $item_loc->setItem($item);
    $item_loc->setQuantity($params['item_quantity_change']);
    $location = $this->loc_repo->find($params['item_location']);
    $item_loc->setLocation($location);
    $this->em->persist($item_loc);
    $this->em->flush();
    // $this->addFlash('success', 'Item Created');

    return $this->redirectToRoute('new_item', ['s' => true]);
  }

  
  /**
   * Function to display and handle item modification forms
   * 
   * @author Daniel Boling
   */
  #[Route('/display_item/', name:'display_item')]
  public function display_item(Request $request): Response
  {
    $id = $request->query->get('item_id');
    $item_loc = $this->item_loc_repo->find($id);
    $item = $item_loc->getItem();
    $locations = $this->loc_repo->findAll();

    $params = [
      'item_id' => $item_loc->getId(),
      'item_name' => $item->getName(),
      'item_desc' => $item->getDescription(),
      'item_exp_date' => null,
      'item_quantity' => $item_loc->getQuantity(),
      'item_location' => $item_loc->getLocation()->getId(),
    ];

    if ($item->getExpDate()) { $params['item_exp_date'] = $item->getExpDate()->format('Y-m-d'); }
    
    if(!$request->request->all())
    {
      return $this->render('item/display_item.html.twig', [
        'locations' => $locations,
        'params' => $params,
        'item_loc' => $item,
      ]);
    }

    $params = $request->request->all();
    $item->setName($params['item_name']);
    $item->setDescription($params['item_desc']);
    $date = new datetime($params['item_exp_date'], new datetimezone('America/Indiana/Indianapolis'));
    $item->setExpDate($date);
    $item_loc->setItem($item);
    $item_loc->setQuantity($item_loc->getQuantity() + ((int)trim($params['quantity_change'], '+')));
    $location = $this->loc_repo->find($params['item_location']);
    $item_loc->setLocation($location);
    $this->em->persist($item_loc);
    $this->em->flush();
    // $this->addFlash('success', 'Item Updated');
    return $this->redirectToRoute('display_item', ['item_id' => $item->getId()]);
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
      // $this->addFlash('success', 'Removed Item Entry');
      return $this->redirectToRoute('items_display');
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
    // $this->addFlash('success', 'Cleared item expiration date');
    return $this->redirectToRoute('display_item', ['item_id' => $id]);
  }

}


// EOF