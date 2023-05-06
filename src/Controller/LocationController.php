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
    $this->date = (new \DateTime('now'))->format('D, j F, Y');
    $this->request_stack = $request_stack;
  }

  
  /**
   * Function to display all locations in the system
   * 
   * @author Daniel Boling
   * 
   * @Route("/locations", name="show_locations")
   */
  public function show_locations(Request $request): Response
  {

    $loc_result = $this->loc_repo->findAll();
    $loc_result = $this->paginator->paginate($loc_result, $request->query->getInt('page', 1), 10);

    $loc = new Location();

    $loc_form = $this->createFormBuilder($loc)
      ->add('name', TextType::class)
      ->add('submit', SubmitType::class,['label' => 'Add Location'])
      ->getForm()
    ;

    $loc_form->handleRequest($request);
    if($loc_form->isSubmitted() && $loc_form->isValid())
    {
      $loc = $loc_form->getData();
      $this->em->persist($loc);
      $this->em->flush();

      return $this->redirectToRoute('show_locations');

    }

    return $this->render('overview_locations.html.twig', [
      'date' => $this->date,
      'loc_result' => $loc_result,
      'loc_form' => $loc_form->createView(),
    ]);

  }


  /**
   * Function to handle location modification
   * 
   * @author Daniel Boling
   * 
   * @Route("/modify/location/{id}", name="modify_location");
   */
  public function modify_location(Request $request, $id): Response
  {

    $loc = $this->loc_repo->find($id);
    $item_loc_result = $loc->getItemlocation();
    $items = $this->paginator->paginate($item_loc_result, $request->query->getInt('page', 1), 10);
    $loc_qty = $this->item_loc_repo->getLocQty($id)[0]['quantity'];


    $form_array = array('modify_form' => $loc);
    $form = $this->createFormBuilder($form_array)
      ->add('modify_form', ModifyFormType::class, ['required' => false])
      ->add('search_form', SearchFormType::class, ['required' => false])
      ->getForm();
    // combine forms


    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
      $form_data = $form->getData();
      $loc_data = $form_data['modify_form'];
      $search_input = $form_data['search_form']['search_input'];

      if(!in_array($search_input, [null, NULL, '', ' '])) {
      // if search_input is null or empty, run search function
        $items = $this->item_loc_repo->findItem($search_input);
        $items = $this->paginator->paginate($items, $request->query->getInt('page', 1), 10);

      } else {
      
        if($form->get('modify_form')->get('modify_submit')->isClicked()) {
          $this->em->persist($loc_data);
          $this->em->flush();
          return $this->redirectToRoute('show_locations');

        } elseif($form->get('modify_form')->get('delete')->isClicked()) {
          if($loc_qty == 0 or $loc_qty == NULL)
          {
            $this->em->remove($loc_data);
            $this->em->flush();
            return $this->redirectToRoute('show_locations');

          }
        }
      }
    }


    return $this->render('modify_location.html.twig', [
      'form' => $form->createView(),
      'items' => $items,
      'date' => $this->date,
    ]);
    
  }


}


// EOF

?>
