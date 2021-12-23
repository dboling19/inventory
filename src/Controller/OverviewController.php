<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Item_Location;
use Doctrine\Common\Collections\ArrayCollection;

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
   * @Route("/items", name="show_items")
   */
  public function show_items(): Response
  {
    $date = new \DateTime();
    $date = $date->format('D, j F, Y');

    $result = $this->getDoctrine()
      ->getRepository(Item::class)
      ->findAll()
    ;

    return $this->render('overview_items.html.twig', [
      'date' => $date,
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
    $em = $this->getDoctrine()->getManager();
    // Required line for modifying database entries

    $date = new \DateTime();
    $date = $date->format('l, j F, Y');

    $item = $this->getDoctrine()
        ->getRepository(Item::class)
        ->find($id)
    ;
    
    $item_loc = $this->getDoctrine()
        ->getRepository(Item::class)
        ->findById($id)
    ;

    $form = $this->createFormBuilder($item)
      ->add('name', TextType::class)
      ->add('location', ChoiceType::class, [
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

      return $this->redirectToRoute('show_items');
    }

    return $this->render('modify_item.html.twig', [
      'form' => $form->createView(),
      'date' => $date,
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
    $em = $this->getDoctrine()->getManager();
    // Required line for modifying database entries

    $date = new \DateTime();
    $date = $date->format('l, j F, Y');

    $item = new Item();

    $form = $this->createFormBuilder($item)
      ->add('name', TextType::class)
      ->add('location', ChoiceType::class, [
        'choices' => [
          $em->getRepository(Location::class)
            ->findAll()
        ],
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
      $em->persist($item);
      $em->flush();

      return $this->redirectToRoute('show_items');
    }

    return $this->render('new_item.html.twig', [
      'form' => $form->createView(),
      'date' => $date,
    ]);
    
  }


  /**
   * Function to control the addition of new locations
   * 
   * @author Daniel Boling
   * 
   * @Route("/new/location", name="new_location")
   */
  public function add_location(Request $request): Response
  {
    $em = $this->getDoctrine()->getManager();
    // Required line for modifying database entries

    $date = new \DateTime();
    $date = $date->format('l, j F, Y');

    $loc = new Location();

    $form = $this->createFormBuilder($loc)
      ->add('name', TextType::class)
      ->add('submit', SubmitType::class,['label' => 'Add Location'])
      ->getForm()
    ;

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $loc = $form->getData();
      $em->persist($loc);
      $em->flush();

      return $this->redirectToRoute('show_locations');
    }

    return $this->render('new_location.html.twig', [
      'form' => $form->createView(),
      'date' => $date,
    ]);

  }

  
  /**
   * Function to display all locations in the system
   * 
   * @author Daniel Boling
   * 
   * @Route("/locations", name="show_locations")
   */
  public function show_locations(): Response
  {
    $date = new \DateTime();
    $date = $date->format('D, j F, Y');

    $result = $this->getDoctrine()
      ->getRepository(Location::class)
      ->findAll()
    ;

    return $this->render('overview_locations.html.twig', [
      'date' => $date,
      'result' => $result,
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
    $em = $this->getDoctrine()->getManager();
    // Required line for modifying database entries

    $date = new \DateTime();
    $date = $date->format('l, j F, Y');

    $loc = $this->getDoctrine()
        ->getRepository(Location::class)
        ->find($id)
    ;

    $items = $loc->getItems();

    $options_array = array('label' => 'Delete Location', 'disabled' => true);
    if(count($items) == 0)
    {
      $options_array['disabled'] = false;
    } else {
      $options_array['disabled'] = true;
    }

    $form = $this->createFormBuilder($loc)
      ->add('name', TextType::class)
      ->add('submit', SubmitType::class, ['label' => 'Modify Location'])
      ->add('delete', SubmitType::class, $options_array)
      ->getForm()
    ;
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      if($form->get('submit')->isClicked()){
        $loc = $form->getData();
        $em->persist($loc);
        $em->flush();
      } elseif($form->get('delete')->isClicked()) {
        $loc = $form->getData();
        $em->remove($loc);
        $em->flush();
      }

      return $this->redirectToRoute('show_locations');
    }

    return $this->render('modify_location.html.twig', [
      'form' => $form->createView(),
      'items' => $items,
      'date' => $date,
    ]);
    
  }

}

// EOF
