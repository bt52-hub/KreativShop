<?php

namespace App\Form;

use App\Entity\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class TemplateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Format name (e.g. Square, Landscape)',
                'constraints' => [new NotBlank()],
            ])
            ->add('width', IntegerType::class, [
                'label' => 'Width (cm)',
                'constraints' => [new NotBlank(), new Positive()],
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Height (cm)',
                'constraints' => [new NotBlank(), new Positive()],
            ])
            ->add('basePrice', NumberType::class, [
                'label' => 'Base price (€)',
                'scale' => 2,
                'constraints' => [new NotBlank(), new Positive()],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Template::class]);
    }
}
