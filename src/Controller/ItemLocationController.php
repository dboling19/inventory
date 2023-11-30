<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\LocationRepository;
use App\Repository\TransactionRepository;
use App\Repository\ItemLocationRepository;
use App\Repository\UnitRepository;
use App\Repository\WarehouseRepository;
use App\Service\TransactionService;
use Knp\Component\Pager\PaginatorInterface;
use Datetime;
use Datetimezone;


class ItemLocationController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em,
    private ItemRepository $item_repo,
    private LocationRepository $loc_repo,
    private TransactionRepository $trans_repo,
    private ItemLocationRepository $item_loc_repo,
    private UnitRepository $unit_repo,
    private WarehouseRepository $whs_repo,
    private TransactionService $trans_service,
    private PaginatorInterface $paginator,
  ) { }


  /**
   * Detail item location relationships.
   * 
   * @author Daniel Boling
   */
  #[Route('/item_loc/details/', 'detail_item_loc')]
  public function detail_item_loc(Request $request): Response
  {
    $params = [
      'item_code' => '',
      'item_desc' => '',
      'item_total_qty' => '',
    ];
    $entity_type = 'item_loc';

    if (!$request->query->get('item_code'))
    {
      return $this->render('item_loc/item_loc_details.html.twig', [
        'locations' => $this->loc_repo->findAll(),
        'warehouses' => $this->whs_repo->findAll(),
        'items' => $this->item_repo->findAll(),
        'item_loc' => null,
        'params' => $params,
        'entity_type' => $entity_type,
      ]);
    }
    // no item_code

    
    $item = $this->item_repo->find($request->query->get('item_code'));
    $item_locations = [];
    foreach ($item->getItemLoc() as $item_loc)
    {
      $item_locations[] = ['loc_code' => $item_loc->getLocation()->getLocCode(), 'whs_code' => $item_loc->getWarehouse()->getWhsCode(), 'item_qty' => $item_loc->getItemQty()];
    }

    $params = [
      'item_code' => $item->getItemCode(),
      'item_desc' => $item->getItemDesc(),
      'item_total_qty' => $item->getItemQty(),
      'item_locations' => $item_locations,
    ];

    if (!$request->request->all()) {
      return $this->render('item_loc/item_loc_details.html.twig', [
        'locations' => $this->loc_repo->findAll(),
        'warehouses' => $this->whs_repo->findAll(),
        'items' => $this->item_repo->findAll(),
        'item_loc' => $item->getItemLoc(),
        'params' => $params,
        'entity_type' => $entity_type,
      ]);
    }

  }


  /**
   * Create item location relationships
   * 
   * @author Daniel Boling
   */
  #[Route('/item_loc/new/', '')]
  public function new_item_loc(Request $request): Response
  {
    if (!$request->query->get('item_code'))
    {
      return $this->redirectToRoute('list_item');
    }
    $params = $request->request->all();
    $item = $this->item_repo->find($params['item_code']);
    foreach (explode(',', $params['item_locations']) as $loc_addr)
    {
      $loc = $this->loc_repo->find($loc_addr['loc_code']); 
      $whs = $this->whs_repo->find($loc_addr['whs_code']);
      $item->addLocation($loc, $whs);
    }
    $this->em->persist($item);
    // $this->trans_service->create_transaction($item, $location, ((int)trim($params['quantity_change'], '+')));
    $this->em->flush();
    $this->addFlash('success', 'Location Relationship Updated');
    return $this->redirectToRoute('detail_item_loc', ['item_code' => $request->query->get('item_code')]);
  }


  /**
   * Modify item location relationships.
   * 
   * @author Daniel Boling
   */
  #[Route('/item_loc/modify/', 'item_loc_modify')]
  public function modify_item_loc(Request $request): Response
  {
    if (!$request->query->get('item_code'))
    {
      return $this->redirectToRoute('list_item');
    }
    $params = $request->request->all();
    $item = $this->item_repo->find($params['item_code']);
    foreach (explode(',', $params['item_locations']) as $loc_addr)
    {
      $loc = $this->loc_repo->find($loc_addr['loc_code']); 
      $whs = $this->whs_repo->find($loc_addr['whs_code']);
      $item->addLocation($loc, $whs);
    }
    $this->em->persist($item);
    // $this->trans_service->create_transaction($item, $location, ((int)trim($params['quantity_change'], '+')));
    $this->em->flush();
    $this->addFlash('success', 'Location Relationship Updated');
    return $this->redirectToRoute('item_location_details', ['item_code' => $request->query->get('item_code')]);
  }


  /**
   * Delete item location relationship.
   * This does not delete the item, just removes the item association.
   * This may never be called, but the option is available.
   * 
   * @author Daniel Boling
   */
  #[Route('/item_loc/delete/', 'item_loc_delete')]
  public function delete_item_loc(Request $request): Response
  {
    if (!$request->query->get('item_code'))
    {
      return $this->redirectToRoute('list_item');
    }
    $params = $request->request->all();
    $item = $this->item_repo->find($params['item_code']);
    foreach (explode(',', $params['item_locations']) as $loc_addr)
    {
      $loc = $this->loc_repo->find($loc_addr['loc_code']); 
      $whs = $this->whs_repo->find($loc_addr['whs_code']);
      $item->addLocation($loc, $whs);
    }
    $this->em->persist($item);
    // $this->trans_service->create_transaction($item, $location, ((int)trim($params['quantity_change'], '+')));
    $this->em->flush();
    $this->addFlash('success', 'Location Relationship Updated');

    return $this->redirectToRoute('item_location_details', ['item_code' => $request->query->get('item_code')]);
  }

}
