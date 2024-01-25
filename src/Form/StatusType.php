<?php

namespace App\Form;

use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StatusType extends AbstractType
{
  public function __construct(
    private UrlGeneratorInterface $router,
  ) {}

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('status_code', TextType::class, [
        'label' => 'Status Code',
        'required' => true,
      ])
      ->add('status_desc', TextType::class, [
        'label' => 'Status Desc',
        'required' => false,
      ])
      ->add('status_notes', TextareaType::class, [
        'label' => 'Status Notes',
        'required' => false,
      ])
      ->add('new', SubmitType::class, [
        'label' => 'New',
        'attr' => [
          'form' => $builder->getName() . '_form',
          'formaction' => $this->router->generate($builder->getName() . '_list'),
        ],
      ])
      ->add('search', SubmitType::class, [
        'label' => 'Search',
        'attr' => [
          'form' => $builder->getName() . '_form',
          'formaction' => $this->router->generate($builder->getName() . '_search'),
          'formmethod' => 'post',
        ],
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Save',
        'attr' => [
          'form' => $builder->getName() . '_form',
          'formaction' => $this->router->generate($builder->getName() . '_save'),
        ],
      ])
      ->add('delete', SubmitType::class, [
        'label' => 'Delete',
        'disabled' => true,
        'attr' => [
          'form' => $builder->getName() . '_form',
          'formaction' => $this->router->generate($builder->getName() . '_delete'),
        ],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Status::class,
      'attr' => ['id' => 'status_form'],
    ]);
  }
}
