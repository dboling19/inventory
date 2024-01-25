<?php

namespace App\Form;

use App\Entity\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('route', EntityType::class, [
        'class' => Route::class,
        'expanded' => false,
        'multiple' => true,
        'choice_label' => 'name',
        'empty_data' => 'No Routes Found',
      ])

    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Route::class,
    ]);
  }
}
