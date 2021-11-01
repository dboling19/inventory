<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Item;
use App\Entity\Location;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class OverviewController extends AbstractController
{
  /**
   * Function to display all items in the system
   * 
   * @author Daniel Boling
   * 
   * @Route("/overview", name="show_all")
   */
  public function showAll(): Response
  {
    $date = new \DateTime();
    $date = $date->format('D, j F, Y');

    $item = $this->getDoctrine()
      ->getRepository(Item::class)
      ->findAll()
    ;

    return $this->render('overview.html.twig', [
      'date' => $date,
      'item' => $item,
    ]);
  }

  /**
   * Function to display and handle add forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/modify/{id}", name="modify_item");
   */
  public function modify_item(Request $request, $id): Response
  {
    $em = $this->getDoctrine()->getManager();
    // Required line for modifying database entries

    $date = new \DateTime();
    $date = $date->format('l, j F, Y');

    $item = $this->getDoctrine()
        ->getRepository(Item::class)
        ->findById($id)
    ;
    
    $item_loc = $this->getDoctrine()
        ->getRepository(Item::class)
        ->findById($id)
    ;

    $form = $this->createFormBuilder($item)
      ->add('name', TextType::class, [
          'data' => $item['Name'],
      ])
      ->add('loc', ChoiceType::class, [
        'choices' => [
          $em->getRepository(Location::class)
            ->findAll()
        ],
        'choice_label' => 'name',
        'label' => 'Location',
      ])
      ->add('quantity', IntegerType::class)
      ->add('submit', SubmitType::class, ['label' => 'Store Item'])
      ->getForm()
      ;
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $item = $form->getData();
      $em->persist($item);
      $em->flush();

      return $this->redirectToRoute('showAll');
    }

    return $this->render('modify_item.html.twig', [
      'form' => $form->createView(),
      'date' => $date,
    ]);
    
  }


  /**
   * Function to display and handle add forms
   * 
   * @author Daniel Boling
   * 
   * @Route("/new", name="new_item");
   */
  public function new_item(Request $request): Response
  {
    $em = $this->getDoctrine()->getManager();
    // Required line for modifying database entries

    $date = new \DateTime();
    $date = $date->format('l, j F, Y');

    $item = new Item();

    $form = $this->createFormBuilder($item)
      ->add('name', TextType::class)
      ->add('loc', ChoiceType::class, [
        'choices' => [
          $em->getRepository(Location::class)
            ->findAll()
        ],
        'label' => 'Location',
        'choice_label' => 'name',
      ])
      ->add('quantity', IntegerType::class)
      ->add('unit', ChoiceType::class, [
        'choices' => [
          'Box(es)' => 'Box(es)',
          'Jars' => 'Jars',
          'Cans' => 'Cans',
          'Pounds - lbs' => 'lbs',
          'Ounces - oz' => 'oz',
          'Package' => 'pkg',
          'Gallon' => 'gallon',
        ],
      ])
      ->add('submit', SubmitType::class, ['label' => 'Add New Item'])
      ->getForm()
      ;
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $item = $form->get('name', 'quantity', 'unit')->getData();
      $loc = $form->get('loc')->getData();
      $em->addLoc($loc);
      $em->persist($item);
      $em->persist($loc);
      $em->flush();

      return $this->redirectToRoute('showAll');
    }

    return $this->render('new_item.html.twig', [
      'form' => $form->createView(),
      'date' => $date,
    ]);
    
  }

}

// EOF