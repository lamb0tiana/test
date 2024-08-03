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
    public const options = [Options::Text->name, Options::Textarea->name,  Options::Number->name, Options::Date->name, Options::Select->name, Options::Boolean->name, Options::Email->name];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, ['label' => false, 'attr' => ['placeholder' => 'Libellé du champ']])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'attr' => ['class' => 'field-type-selection'],
                'placeholder' => 'Type de champ',
                'choices' => [
                    Options::Text->name => Options::Text->value,
                    Options::Email->name => Options::Email->value,
                    Options::Textarea->name => Options::Textarea->value,
                    Options::Select->name => Options::Select->value,
                    Options::Date->name => Options::Date->value,
                    Options::Boolean->name => Options::Boolean->value,
                    Options::Number->name => Options::Number->value,
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
