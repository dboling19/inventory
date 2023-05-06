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

use App\Form\ItemType;
use App\Form\ItemLocationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ItemController extends AbstractController
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
    $this->date = (new \DateTime('now', new \DateTimeZone('America/Indiana/Indianapolis')))->format('Y-m-d');
    $this->request_stack = $request_stack;
  }

  /**
   * Function to display all items in the system
   * 
   * @author Daniel Boling
   * 
   * @Route("/", name="show_items")
   */
  public function show_items(Request $request): Response
  {
    if ($request->cookies->get('items_limit') != null)
    {
      $limit = array('items_limit' => $request->cookies->get('items_limit'));
    } else {
      $limit = array('items_limit' => 25);
    }

    $params = [
      'item_name' => '',
      'location' => '',
      'limit' => $limit['items_limit'],
    ];


    if($request->query->all())
    {
      $params = $request->query->all();
      if (isset($params['limit']) && $limit['items_limit'] == $params['limit'])
      {
        $cookie = new Cookie('items_limit', $params['limit']);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();  
        $limit['items_limit'] = $params['limit'];
      }
      $result = $this->item_loc_repo->findItem($params);
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['items_limit']);

    } else {
      $result = $this->item_loc_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['items_limit']);

    }

    return $this->render('overview_items.html.twig', [
      'locations' => $this->loc_repo->findAll(),
      'result' => $result,
      'params' => $params,
    ]);

  }


  /**
   * Function to display and handle new item forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/new/item", name="new_item");
   */
  public function new_item(Request $request, $submitted = false): Response
  {
    $post_data = [
      'date' => $this->date,
    ];
    $locations = $this->em
      ->getRepository(Location::class)
      ->createQueryBuilder('l')
      ->getQuery()
      ->getArrayResult()
    ;
    
    if($form_data = $request->query->all())
    {
      $item = new Item;
      $item_loc = new ItemLocation;
      $item->setName($form_data['name']);
      $item->setDescription($form_data['desc']);
      $date = new \DateTime($form_data['date'], new \DateTimeZone('America/Indiana/Indianapolis'));
      $item->setExpDate($date);
      $item_loc->setItem($item);
      $item_loc->setQuantity($form_data['quantity_change']);
      $location = $this->loc_repo->find($form_data['location']);
      $item_loc->setLocation($location);
      $this->em->persist($item_loc);
      $this->em->flush();

      $submitted = true;
    }

    return $this->render('new_item.html.twig', [
      'submitted' => $submitted,
      'locations' => $locations,
      'post_data' => $post_data,
    ]);
    
  }

  
  /**
   * Function to display and handle item modification forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/modify/item/{id}", name="modify_item");
   */
  public function modify_item(Request $request, $id): Response
  {

    $item_loc = $this->item_loc_repo->find($id);
    $item = $item_loc->getItem();

    $form = $this->createForm(ItemLocationType::class, $item_loc);
    if($item_loc->getQuantity() == 0)
    // disable delete button if items are in location
    {
      $form->add('delete', SubmitType::class, [
        'label' => 'Delete Entry',
        'disabled' => false,
      ]);
    } else {
      $form->add('delete', SubmitType::class, [
        'label' => 'Delete Entry',
        'disabled' => true,
      ]);
    }
    $form->add('submit', SubmitType::class, ['label' => 'Modify Entry']);
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      if($form->get('submit')->isClicked())
      {
        $item_loc_result = $form->getData();
        $item_result = $form->get('item')->getData();
        if (!$form->get('quantityChange')->getData()) {
          $quantity_change = '0';
        } else {
          $quantity_change = $form->get('quantityChange')->getData();
        }
        $item_loc->setupItem($item_result->getName(), $item_result->getDescription(), $item_loc_result->getQuantity() + ((int)trim($form->get('quantityChange')->getData(), '+')), $quantity_change, $item_loc_result->getLocation());
        // setupItem(?string $name, ?string $desc, ?int $quantity, ?string $change, ?Location $loc)
        $this->em->persist($item_loc);
        // update item record
        $this->em->flush();

        return $this->render('modify_item.html.twig', [
          'form' => $form->createView(),
          'item_quantity' => $item_loc->getQuantity(),
          'date' => $this->date,
        ]);

      } elseif ($form->get('delete')->isClicked()) {
        if ($item_loc->getQuantity() == 0)
        {
          $this->em->remove($item_loc);
          $this->em->flush();
          return $this->redirectToRoute('show_items');
        
        }
      }
    }

    return $this->render('modify_item.html.twig', [
      'form' => $form->createView(),
      'item_quantity' => $item_loc->getQuantity(),
      'date' => $this->date,
    ]);


  }


}


// EOF

?>
