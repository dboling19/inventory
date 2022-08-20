<?php

namespace App\Form;

use App\Entity\ItemLocation;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class ItemLocationType extends AbstractType
{

  public function __construct(LocationRepository $loc_repo)
  {
    $this->loc_repo = $loc_repo;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('item', ItemType::class)
      ->add('location', ChoiceType::class, [
        'choice_loader' => new CallbackChoiceLoader(function() {
          return $this->loc_repo->findAll();
        }),
        'placeholder' => 'Choose an option',
        'choice_label' => 'name',
        'label' => 'Location',
        ])
      ->add('quantityChange', TextType::class, [
        'mapped' => false,
        'required' => false,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ItemLocation::class,
    ]);
  }
  
}
