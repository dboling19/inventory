<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $em,
    private StatusRepository $status_repo,
    private PaginatorInterface $paginator,
  ) {}


  #[Route('/status_list/', name: 'status_list')]
  public function status_list(Request $request): Response
  {
    if (isset($request->query->all()['status_code']))
    {
      $status = $this->status_repo->find($request->query->all()['status_code']);
    } else {
      $status = new Status;
    }
    $status_form = $this->createForm(StatusType::class, $status);
    $status_thead = [
      'status_code' => 'Status Code',
      'status_desc' => 'Status Desc',
      'status_notes' => 'Status Notes',
    ];
    // to autofill form fields, or leave them null.
    $result = $this->status_repo->findAll();
    $result = $this->paginator->paginate($result, $request->query->getInt('page', 1), 100);
    $normalized_status = [];
    foreach ($result->getItems() as $item) {
      $normalized_status[] = [
        'status_code' => $item->getStatusCode(),
        'status_desc' => $item->getStatusDesc(),
        'status_notes' => $item->getStatusNotes(),
      ];
    }
    $result->setItems($normalized_status);
    return $this->render('status/status_list.html.twig', [
      'result' => $result,
      'status_thead' => $status_thead,
      'form' => $status_form,
    ]);
  }

    /**
   * Search for status using status_code.
   * Should be the standard route for querying for status
   * 
   * @author Daniel Boling
   */
  #[Route('/status/search/', name:'status_search')]
  public function status_search(Request $request): Response
  {
    $status_form = $this->createForm(StatusType::class);
    $status_form->handleRequest($request);
    $status = $status_form->getData();
    if (!$status->getStatusCode()) { return $this->redirectToRoute('status_list'); }

    return $this->redirectToRoute('status_list', [
      'status_code' => $status->getStatusCode(),
    ]); 
  }


  /**
   * Creates statusation from list_status page
   * 
   * @author Daniel Boling
   */
  #[Route('/status/new/', name:'status_create')]
  public function status_create(Request $request): Response
  {
    $status_form = $this->createForm(StatusType::class);
    $status_form->handleRequest($request);
    $status = $status_form->getData();
    $this->em->persist($status);
    $this->em->flush();
    $this->addFlash('success', 'Status Created');

    return $this->redirectToRoute('status_list', [
      'status_code' => $status->getStatusCode(),
    ]);
  }


  /**
   * Modifies status details and redirects back to status_list
   * 
   * @author Daniel Boling
   */
  #[Route('/status/save/', name:'status_save')]
  public function status_save(Request $request): Response
  {
    $status_form = $this->createForm(StatusType::class);
    $status_form->handleRequest($request);
    $status = $status_form->getData();
    if (!$status_form->isValid())
    {
      $this->addFlash('error', 'Error: Invalid Submission - statusation not updated');
      return $this->redirectToRoute('status_search', ['status_code' => $status->getstatusCode()]);
    }
    if ($this->status_repo->find($status->getstatusCode())) {
      return $this->redirectToRoute('status_modify', ['status' => $status], 307);
    } else {
      return $this->redirectToRoute('status_create', ['status' => $status], 307);
    }
  }


  /**
   * Handle item modification
   * 
   * @author Daniel Boling
   */
  #[Route('/status/modify/', name:'status_modify')]
  public function status_modify(Request $request): Response
  {
    $status_form = $this->createForm(StatusType::class);
    $status_form->handleRequest($request);
    $status = $status_form->getData();
    $this->em->merge($status);
    $this->em->flush();
    $this->addFlash('success', 'Status Updated');
    return $this->redirectToRoute('status_list', ['status_code' => $status->getstatusCode()]);
  }


  /**
   * Deletes status if no entites are under it and redirects back to show status
   * 
   * @author Daniel Boling
   */
  #[Route('/status/delete/', name:'status_delete')]
  public function status_delete(Request $request): Response
  {
    $status_form = $this->createForm(StatusType::class);
    $status_form->handleRequest($request);
    $status = $status_form->getData();
    $status = $this->status_repo->find($status->getStatusCode());
    $this->em->remove($status);
    $this->em->flush();
    return $this->redirectToRoute('status_list');
    $this->addFlash('success', 'Status removed.');
  }
}
