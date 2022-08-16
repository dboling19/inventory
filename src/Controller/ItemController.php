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
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
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

class ItemController extends AbstractController
{

  public function __construct(EntityManagerInterface $em, ItemRepository $item_repo, LocationRepository $loc_repo, PaginatorInterface $paginator, RequestStack $request_stack)
  {
    $this->em = $em;
    $this->item_repo = $item_repo;
    $this->loc_repo = $loc_repo;
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
    if ($request->cookies->get('limit') != null)
    {
      $limit = array('limit' => $request->cookies->get('limit'));
      echo "Found Cookie!";

    } else {
      $limit = array('limit' => 10);
      echo "No Cookie Found!";
      
    }

    $search = array('search_input' => '');
    $search_form = $this->createFormBuilder($search)
      ->add('search_input', SearchType::class, ['label' => 'Search', 'required' => false])
      ->add('search_submit', SubmitType::class)
      ->getForm()
    ;

    $limit_form = $this->createFormBuilder($limit)
      ->add('limit_choice', ChoiceType::class, [
        'choices' => [
          '5' => 5,
          '10' => 10,
          '25' => 20,
          '50' => 50,
          '100' => 100,
        ],
      ])
      ->add('limit_submit', SubmitType::class, ['label' => 'Limit'])
      ->getForm()
    ;


    $limit_form->handleRequest($request);
    if($limit_form->isSubmitted() && $limit_form->isValid())
    {
      $limit = $limit_form->getData();
      $cookie = new Cookie('limit', $limit['limit_choice']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $result = $this->item_repo->findItem($search['search_input']);
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['limit_choice']);

    } else {
      $result = $this->item_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['limit']);

    }

    $search_form->handleRequest($request);
    if($search_form->isSubmitted() && $search_form->isValid())
    {
      $search = $search_form->getData();
      $result = $this->item_repo->findItem($search['search_input']);
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
   * Function to display and handle item modification forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/modify/item/{id}", name="modify_item");
   */
  public function modify_item(Request $request, $id): Response
  {

    $item = $this->item_repo->find($id);

    $form = $this->createFormBuilder($item)
      ->add('name', TextType::class)
      ->add('location', ChoiceType::class, [
        'choice_loader' => new CallbackChoiceLoader(function() {
          return $this->loc_repo->findAll();
        }),
        'placeholder' => 'Choose an option',
        'choice_label' => 'name',
        'label' => 'Location',
      ])
      ->add('quantity', IntegerType::class)
      ->add('submit', SubmitType::class, ['label' => 'Modify Item'])
      ->getForm()
      ;
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $item = $form->getData();
      $this->em->persist($item);
      $this->em->flush();
      return $this->redirectToRoute('show_items');
    }

    return $this->render('modify_item.html.twig', [
      'form' => $form->createView(),
      'date' => $this->date,
    ]);
    
  }


  /**
   * Function to display and handle new item forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/new/item", name="new_item");
   */
  public function new_item(Request $request): Response
  {

    $item = new Item();

    $form = $this->createFormBuilder($item)
      ->add('name', TextType::class)
      ->add('location', ChoiceType::class, [
        'choice_loader' => new CallbackChoiceLoader(function() {
          return $this->loc_repo->findAll();
        }),
        'placeholder' => 'Choose an option',
        'label' => 'Location',
        'choice_label' => 'name',
      ])
      ->add('quantity', IntegerType::class)
      ->add('submit', SubmitType::class, ['label' => 'Add Item'])
      ->getForm()
      ;
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $item = $form->getData();
      $this->em->persist($item);
      $this->em->flush();

      return $this->redirectToRoute('show_items');
    }

    return $this->render('new_item.html.twig', [
      'form' => $form->createView(),
      'date' => $this->date,
    ]);
    
  }


}


// EOF
