<?php

namespace App\Controller;

use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;
use App\Form\PurchaseOrderType;
use App\Repository\LocationRepository;
use App\Repository\PurchaseOrderRepository;
use App\Repository\PurchaseOrderLineRepository;
use App\Repository\TermsRepository;
use App\Repository\VendorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PurchaseOrderController extends AbstractController
{
  public function __construct(
    private PurchaseOrderRepository $po_repo,
    private PurchaseOrderLineRepository $po_line_repo,
    private LocationRepository $loc_repo,
    private VendorRepository $vendor_repo,
    private TermsRepository $terms_repo,
    private PaginatorInterface $paginator,
  ) {}

  #[Route('/po/list', name: 'po_list')]
  public function po_list(Request $request): Response
  {
    if (isset($request->query->all()['po_num']))
    {
      $po = $this->po_repo->find($request->query->all()['po_num']);
    } else {
      $po = new PurchaseOrder;
    }
    $po_form = $this->createForm(PurchaseOrderType::class, $po);
    $po_thead = [
      'po_num' => 'PO',
      'po_vendor' => 'Vendor',
      'po_terms' => 'Terms',
      'po_total_cost' => 'PO Total Cost',
      'po_date' => 'PO Date',
    ];
    // to autofill form fields, or leave them null.
    $po_result = $this->po_repo->findAll();
    $po_result = $this->paginator->paginate($po_result, $request->query->getInt('po_page', 1), 100);
    $normalized_pos = [];
    foreach ($po_result->getItems() as $item)
    {
      $normalized_pos[] = [
        'po_num' => $item->getPoNum(),
        'po_vendor' => $item->getPoVendor()->getVendorCode() ?? '',
        'po_terms' => $item->getPoTerms()->getTermsCode() ?? '',
        'po_total_cost' => $item->getPoTotalCost(),
        'po_date' => $item->getPoDate()->format('d-m-Y'),
      ];
    }
    $po_result->setItems($normalized_pos);
    return $this->render('purchase_order/po_list.html.twig', [
      'po_thead' => $po_thead,
      'po_result' => $po_result,
      'form' => $po_form,
    ]);
  }


  /**
   * Mainly a placeholder for the search functionality.
   * 
   * @author Daniel Boling
   */
  #[Route('/po/search/', name:'po_search')]
  public function po_search(Request $request): Response
  {
    $po_form = $this->createForm(PurchaseOrderType::class);
    $po_form->handleRequest($request);
    $po = $po_form->getData();
    if (!$po->getPoNum()) { return $this->redirectToRoute('po_list'); }

    return $this->redirectToRoute('po_list', [
      'po_num' => $po->getPoNum(),
    ]);
  }
  

  /**
   * Handle po form submission.
   * Redirect to creation or modification fuctions
   * 
   * @author Daniel Boling
   */
  #[Route('/po/save/', name:'po_save')]
  public function po_save(Request $request): Response
  {
    $po_form = $this->createForm(PurchaseOrderType::class);
    $po_form->handleRequest($request);
    $po = $po_form->getData();
    if (!$po_form->isValid())
    {
      $this->addFlash('error', 'Error: Invalid Submission - Purchase Order not updated');
      return $this->redirectToRoute('po_search', ['po_num' => $po->getPoNum()]);
    }
    if ($this->po_repo->find($po->getPoNum())) {
      return $this->redirectToRoute('po_modify', ['po' => $po], 307);
    } else {
      return $this->redirectToRoute('po_create', ['po' => $po], 307);
    }
  }


  /**
   * Handle po modification
   * 
   * @author Daniel Boling
   */
  #[Route('/po/modify/', name:'po_modify')]
  public function po_modify(Request $request): Response
  {
    $po_form = $this->createForm(PurchaseOrderType::class);
    $po_form->handleRequest($request);
    $po = $po_form->getData();
    $this->em->merge($po);
    $this->em->flush();
    $this->addFlash('success', 'Purchase Order Updated');
    return $this->redirectToRoute('po_list', ['po_num' => $po->getPoNum()]);
  }


  /**
   * Handle po creation
   * 
   * @author Daniel Boling
   */
  #[Route('/po/create/', name:'po_create')]
  public function po_create(Request $request): Response
  {
    $po_form = $this->createForm(PurchaseOrderType::class);
    $po_form->handleRequest($request);
    $po = $po_form->getData();
    $this->em->persist($po);
    $this->em->flush();
    $this->addFlash('success', 'Purchase Order Created');
    return $this->redirectToRoute('po_list', ['po_num' => $po->getPoNum()]);
  }


  /**
   * Delete PO only if quantity = 0
   * 
   * @author Daniel Boling
   */
  #[Route('/po/delete/', name:'po_delete')]
  public function po_delete(Request $request)
  {
    $po_form = $this->createForm(PurchaseOrderType::class);
    $po_form->handleRequest($request);
    $po = $po_form->getData();
    $po = $this->po_repo->find($po->getPoNum());

    $this->em->remove($po);
    $this->em->flush();
    $this->addFlash('success', 'Removed Purchse Order Entry');
    return $this->redirectToRoute('po_list');
  }


}
