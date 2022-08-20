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
use Symfony\Component\Validator\Constraints\Count;

class TransactionController extends AbstractController
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
   * Tracks Transaction and compiles them into a tabled page.
   * 
   * @author Daniel Boling
   * 
   * @Route("/transactions", name="show_transactions")
   */
  public function transaction_list(Request $request): Response
  {

    if ($request->cookies->get('trans_limit') != null)
    {
      $limit = array('trans_limit' => $request->cookies->get('trans_limit'));

    } else {
      $limit = array('trans_limit' => 10);
      
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
        'data' => $request->cookies->get('trans_limit'),
      ])
      ->add('limit_submit', SubmitType::class, ['label' => 'Limit'])
      ->getForm()
    ;


    $limit_form->handleRequest($request);
    if($limit_form->isSubmitted() && $limit_form->isValid())
    {
      $limit = $limit_form->getData();
      $cookie = new Cookie('trans_limit', $limit['limit_choice']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $result = $this->trans_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['limit_choice']);

    } else {
      $result = $this->trans_repo->findAll();
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $limit['trans_limit']);

    }

    $search_form->handleRequest($request);
    if($search_form->isSubmitted() && $search_form->isValid())
    {
      $search = $search_form->getData();
      $result = $this->trans_repo->findItem($search['search_input']);
      $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), 10);

    }


    return $this->render('overview_trans.html.twig', [
      'search_form' => $search_form->createView(),
      'limit_form' => $limit_form->createView(),
      'date' => $this->date,
      'result' => $result,
    ]);

  }


}


// EOF

?>
