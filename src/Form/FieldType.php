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
                'attr' => ['class' => 'field-type-selection'],
                'placeholder' => 'Type de champ',
                'choices' => [
                    'Text' => Options::Text->value,
                    'Textarea' => Options::Textarea->value,
                    'Choice' => Options::Choice->value,
                    'Date' => Options::Date->value,
                    'Boolean' => Options::Boolean->value,
                    'Number' => Options::Number->value,
                ]
            ])
            ->add('fieldAttributes', FieldAttributesType::class, ['label' => 'Attributs du champ', 'row_attr' => ['class' => 'attributes-row']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
