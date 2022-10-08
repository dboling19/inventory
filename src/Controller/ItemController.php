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

  public function __construct(EntityManagerInterface $em, ItemRepository $item_repo, LocationRepository $loc_repo, TransactionRepository $trans_repo, ItemLocationRepository $item_loc_repo, PaginatorInterface $paginator, RequestStack $request_stack)
  {
    $this->em = $em;
    $this->item_repo = $item_repo;
    $this->loc_repo = $loc_repo;
    $this->trans_repo = $trans_repo;
    $this->item_loc_repo = $item_loc_repo;
    $this->paginator = $paginator;
    $this->date = (new \DateTime('now'))->format('D, j F, Y');
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
      $limit = array('items_limit' => 10);
      
    }

    $search = array('search_input' => '');
    $search_form = $this->createFormBuilder($search, ['allow_extra_fields' => true])
      ->add('search_input', SearchType::class, ['label' => 'Search', 'required' => false])
      ->add('search_submit', SubmitType::class, ['label' => 'Search'])
      ->getForm()
    ;

    $limit_form = $this->createFormBuilder($limit, ['allow_extra_fields' => true])
      ->add('limit_choice', ChoiceType::class, [
        'choices' => [
          '5' => 5,
          '10' => 10,
          '25' => 20,
          '50' => 50,
          '100' => 100,
        ],
        'data' => $request->cookies->get('items_limit'),
      ])
      ->add('limit_submit', SubmitType::class, ['label' => 'Limit'])
      ->getForm()
    ;


    $limit_form->handleRequest($request);
    if($limit_form->isSubmitted() && $limit_form->isValid())
    {
      $limit = $limit_form->getData();
      $cookie = new Cookie('items_limit', $limit['limit_choice']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $result = $this->item_loc_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['limit_choice']);

    } else {
      $result = $this->item_loc_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['items_limit']);

    }

    $search_form->handleRequest($request);
    if($search_form->isSubmitted() && $search_form->isValid())
    {
      $search = $search_form->getData();
      $result = $this->item_loc_repo->findItem($search['search_input']);
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), 10);

    }

    return $this->render('overview_items.html.twig', [
      'search_form' => $search_form->createView(),
      'limit_form' => $limit_form->createView(),
      'date' => $this->date,
      'result' => $result,
    ]);

  }


  /**
   * Function to display and handle new item forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/new/item", name="new_item");
   */
  public function new_item(Request $request, $submitted = False): Response
  {

    $item_loc = new ItemLocation();

    $form = $this->createForm(ItemLocationType::class, $item_loc);
    $form->add('submit', SubmitType::class, ['label' => 'Create Entry']);
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $item_loc_result = $form->getData();
      $item_result = $form->get('item')->getData();
      $item_loc->setupItem($item_result->getName(), $item_result->getDescription(), $item_loc_result->getQuantity() + ((int)trim($form->get('quantityChange')->getData(), '+')), $form->get('quantityChange')->getData(), $item_loc_result->getLocation());
      // setupItem(?string $name, ?string $desc, ?int $quantity, ?string $change, ?Location $loc)
      $this->em->persist($item_loc);
      // update item record
      $this->em->flush();

      $item_loc = new ItemLocation();

      $form = $this->createForm(ItemLocationType::class, $item_loc);
      $form->add('submit', SubmitType::class, ['label' => 'Create Entry']);

      $submitted = True;
    }

    return $this->render('new_item.html.twig', [
      'form' => $form->createView(),
      'submitted' => $submitted,
      'date' => $this->date,
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
