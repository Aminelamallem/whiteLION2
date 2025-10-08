<?php

namespace App\Form;

use App\Entity\Color;
use App\Entity\Gender;
use App\Entity\Size;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colors', EntityType::class, [
                'class' => Color::class,
                'label' => 'Couleurs',
                'choice_label' => 'label',
                'required' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->innerJoin('c.productVariants', 'pv')
                        ->where('pv = :pv')
                        ->setParameter('pv', $options['productVariant']);
                        //en sql sa donne
                        // SELECT c.*
                        // FROM colors c
                        // INNER JOIN product_variants_colors pvc ON c.id = pvc.color_id
                        // INNER JOIN product_variants pv ON pvc.product_variant_id = pv.id
                        // WHERE pv.id = :pv
                        // ce qui permet de récupérer uniquement les couleurs associées à la variante de produit spécifiée
                }
            ])
            ->add('genders', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'label',
                'required' => true,
                'expanded' => true,
            
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('g')
                        ->innerJoin('g.productVariants', 'pv')
                        ->where('pv = :pv')
                        ->setParameter('pv', $options['productVariant']);
                        //en sql sa donne
                        // SELECT g.*
                        // FROM genders g
                        // INNER JOIN product_variants_genders pvg ON g.id = pvg.gender_id
                        // INNER JOIN product_variants pv ON pvg.product_variant_id = pv.id
                        // WHERE pv.id = :pv
                        // ce qui permet de récupérer uniquement les genres associées à la variante de produit spécifiée
                },
            ])
            ->add('sizes', EntityType::class, [
                'class' => Size::class,
                'choice_label' => 'label',
                'required' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('s')
                        ->innerJoin('s.productVariants', 'pv')
                        ->where('pv = :pv')
                        ->setParameter('pv', $options['productVariant']);
                        //en sql sa donne
                        // SELECT s.*
                        // FROM sizes s
                        // INNER JOIN product_variants_sizes pvs ON s.id = pvs.size_id
                        // INNER JOIN product_variants pv ON pvs.product_variant_id = pv.id
                        // WHERE pv.id = :pv
                        // ce qui permet de récupérer uniquement les tailles associées à la variante de produit spécifiée
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'productVariant' => null,
        ]);
    }
}
