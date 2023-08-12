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


class TransactionController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em,
    private ItemRepository $item_repo,
    private LocationRepository $loc_repo,
    private TransactionRepository $trans_repo,
    private ItemLocationRepository $item_loc_repo,
    private PaginatorInterface $paginator,
    private RequestStack $request_stack,
  ) { }

  
  /**
   * lists transactions.
   * 
   * @author Daniel Boling
   */
  #[Route('/transactions/', name:'list_transactions')]
  public function list_transactions(Request $request): Response
  {
    $transactions_limit_cookie = $request->cookies->get('transactions_limit') ?? 25;
    $params = [
      'item_name' => '',
      'location' => '',
      'min_date' => '',
      'max_date' => '',
      'limit' => $transactions_limit_cookie,
    ];
    if ($transactions_limit_cookie !== $params['limit'])
    // if form submitted limit != cookie limit then update the cookie
    {
      $cookie = new Cookie('transactions_limit', $params['limit']);
      $response = new Response();
      $response->headers->setCookie($cookie);
      $response->send();
      $items_limit_cookie = $params['limit'];
    }
    $params = array_merge($params, $request->query->all());
    $result = $this->trans_repo->filter($params);
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), $params['limit']);

    return $this->render('transaction/list_transactions.html.twig', [
      'locations' => $this->loc_repo->findAll(),
      'params' => $params,
      'result' => $result,
    ]);
  }
}


// EOF
