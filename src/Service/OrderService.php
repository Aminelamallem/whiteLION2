<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\ProductVariantRepository;

class OrderService
{

public function __construct(
    private ProductVariantRepository $productVariantRepository,
){

}



    public function getOrderDetailDatas(Order $order): array{
        $jsonDetail = $order->getHistorical();

        $array = [];

        foreach ($jsonDetail as $item) {
            $array[] = [
                'variant' => $this->productVariantRepository->find($item['variant']),
                'color'   => $item['color'],
                'gender'  => $item['gender'],
                'size'  => $item['size'],
                'price' => $item['price'],
            ];

        }

        return $array;
    }
}