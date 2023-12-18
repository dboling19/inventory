<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item_code', TextType::class, [
                'label' => 'Item Code',
            ])
            ->add('item_desc', TextType::class, [
                'label' => 'Item Desc',
            ])
            ->add('item_notes', TextareaType::class, [
                'label' => 'Item Notes',
            ])
            ->add('item_exp_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Item Exp. Date',
            ])
            // single_text widget enables the browser datepicker
            ->add('item_unit', EntityType::class, [
                'class' => Unit::class,
                'choice_label' => 'unit_code',
                'label' => 'Item Unit',
            ])
            ->add('item_qty', TextType::class, [
                'label' => 'Item Total Qty.',
                'disabled' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
