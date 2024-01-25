<?php

namespace App\Form;

use App\Entity\Vendor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class VendorType extends AbstractType
{
  public function __construct(
    private UrlGeneratorInterface $router,
  ) {}


  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('vendor_code', TextType::class, [
        'required' => true,
        'label' => 'Vendor Code',
      ])
      ->add('vendor_desc', TextType::class, [
        'required' => true,
        'label' => 'Vendor Desc',
      ])
      ->add('vendor_notes', TextareaType::class, [
        'required' => false,
        'label' => 'Vendor Notes',
      ])
      ->add('vendor_addr', TextType::class, [
        'required' => false,
        'label' => 'Vendor Addr',
      ])
      ->add('vendor_email', TextType::class, [
        'required' => false,
        'label' => 'Vendor Email',
      ])
      ->add('vendor_phone', TelType::class, [
        'required' => false,
        'label' => 'Vendor Phone',
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
      'data_class' => Vendor::class,
      'attr' => ['id' => 'vendor_form'],
    ]);
  }
}
