<?php

namespace App\Service;

use App\Repository\SizeRepository;
use App\Repository\ColorRepository;
use App\Repository\GenderRepository;
use App\Repository\ProductVariantRepository;

class CartService
{
    public function __construct(
        private ProductVariantRepository $variantRepository,
        private GenderRepository $genderRepository,
        private ColorRepository $colorRepository,
        private SizeRepository $sizeRepository
    ) {}

    /**
     * Ajoute un produit dans le panier
     */
    public function addToCart(  $productVariant , $formData, array $cart): array
    {
        $cart[] = [
            // clef                       valeur
            'productVariantId' => $productVariant->getId(),
            'price'            => $productVariant->getProduct()->getPrice(),
            'colorId'          => $formData['colors']->getId(),
            'genderId'         => $formData['genders']->getId(),
            'sizeId'           => $formData['sizes']->getId(),
        ];

        return $cart;
    }

    /**
     * Transforme les IDs stockés en entités complètes
     */
    public function getEntitiesFromCart(array $cart): array
    {
        $cartUpToDate = [
            'produits' => [],
            'total'    => 0,
        ];

        foreach ($cart as $item) {
            $cartUpToDate['produits'][] = [
                                    //    se sont le nom des clef que jutilise en haut 
                'variant' => $this->variantRepository->find($item['productVariantId']),
                'color'   => $this->colorRepository->find($item['colorId']),
                'gender'  => $this->genderRepository->find($item['genderId']),
                'size'    => $this->sizeRepository->find($item['sizeId']),
                'price'   => $item['price'],
            ];

            $cartUpToDate['total'] += $item['price'];
        }

        return $cartUpToDate;
    }
}
