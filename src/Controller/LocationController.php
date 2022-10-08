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

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class LocationController extends AbstractController
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


    $modify_form = $this->createFormBuilder($loc, ['allow_extra_fields' => true])
      ->add('name', TextType::class)
      ->add('modify_submit', SubmitType::class, ['label' => 'Rename Location'])
      ->getForm();


    if($loc_qty == 0)
    // disable delete button if items are in location
    {
      $modify_form->add('delete', SubmitType::class, [
        'label' => 'Delete Entry',
        'disabled' => false,
      ]);
    } else {
      $modify_form->add('delete', SubmitType::class, [
        'label' => 'Delete Entry',
        'disabled' => true,
      ]);
    }

    $search = array();
    $search_form = $this->createFormBuilder($search, ['allow_extra_fields' => true])
      ->add('search_input', SearchType::class)
      ->add('search_submit', SubmitType::class)
      ->getForm()
    ;
    // search for items

    $search_form->handleRequest($request);
    if($search_form->isSubmitted() && $search_form->isValid())
    {
      $search = $search_form->getData();
      $items = $this->item_loc_repo->findItem($search['search_input']);
      $items = $this->paginator->paginate($items, $request->query->getInt('page', 1), 10);

    }


    $modify_form->handleRequest($request);
    if($modify_form->isSubmitted() && $modify_form->isValid())
    {
      if($modify_form->get('modify_submit')->isClicked()){
        $loc = $modify_form->getData();
        $this->em->persist($loc);
        $this->em->flush();
        return $this->redirectToRoute('show_locations');

      } elseif($modify_form->get('delete')->isClicked()) {
        if($loc_qty == 0 or $loc_qty == NULL)
        {
          $this->em->remove($loc);
          $this->em->flush();
          return $this->redirectToRoute('show_locations');

        }
      }
    }

    return $this->render('modify_location.html.twig', [
      'search_form' => $search_form->createView(),
      'modify_form' => $modify_form->createView(),
      'items' => $items,
      'date' => $this->date,
    ]);
    
  }


}


// EOF

?>
