<?php

namespace App\Form;

use App\Entity\ProductVariant;
use App\Entity\Size;
use App\Entity\Color;
use App\Entity\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpeg, png, webp)',
                    ])
                ],
            ])
            ->add('sizes', EntityType::class, [
                'class' => Size::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true, // transforme en checkboxes
            ])
            ->add('colors', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true, // transforme en checkboxes
            ])
            ->add('genders', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true, // transforme en checkboxes
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductVariant::class,
        ]);
    }
}
