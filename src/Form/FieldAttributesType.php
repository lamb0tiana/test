<?php

namespace App\Form;

use App\Entity\Field;
use App\Entity\FieldAttributes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        $builder
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
                'isExpanded',
                ChoiceType::class,
                [
                    'choices' => ['Oui' => true, 'Non' => false],
                    'label' => false,
                    'placeholder' => 'Est-ce multiple ?'
                ]
            )
            ->add('options', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FieldAttributes::class,
        ]);
    }
}
