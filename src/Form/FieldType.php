<?php

namespace App\Form;

use App\Entity\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\TypeDefinition\FieldType as Options;

class FieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, ['label' => false, 'attr' => ['placeholder' => 'Libellé du champ']])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Type de champ',
                'choices' => [
                    'Text' => Options::Text,
                    'Textarea' => Options::Textarea,
                    'Choice' => Options::Choice,
                    'Date' => Options::Date,
                    'Boolean' => Options::Boolean,
                    'Number' => Options::Number,
                    'Select' => Options::Select,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
