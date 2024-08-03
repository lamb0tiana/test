<?php

namespace App\Form;

use App\Entity\FieldAttributes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldAttributesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // I defined the name of the prototype as __option_row__  as I need to set the index and not to be confused with parent name
        $builder
            ->add('options', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Définir les options',
                'row_attr' => ['class' => 'd-none'],
                'prototype_name' => '__option_row__',
                'entry_options' => [
                    'attr' => [
                        'placeholder' => 'Option',
                    ],
                ],
            ])
            ->add(
                'required',
                ChoiceType::class,
                [
                    'choices' => ['Oui' => true, 'Non' => false],
                    'label' => false,
                    'placeholder' => 'Est-ce requis ?'
                ]
            )
            ->add(
                'expanded',
                ChoiceType::class,
                [
                    'choices' => ['Oui' => true, 'Non' => false],
                    'label' => false,
                    'placeholder' => 'Est-ce multiple ?'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FieldAttributes::class,
        ]);
    }
}
