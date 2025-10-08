<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductVariantRepository;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class AdminOrderController extends AbstractController
{

public function __construct(
    private OrderService $orderService
){

}


   #[Route('/admin/order', name: 'app_admin_order')]
public function index(OrderRepository $orderRepository): Response
{
    $orders = $orderRepository->findAll();

    return $this->render('admin_order/index.html.twig', [
        'controller_name' => 'AdminOrderController',
        'orders' => $orders,
    ]);
}

 #[Route('/admin/order/{id}', name: 'app_admin_order_detail')]
public function detail(string $id, OrderRepository $orderRepository): Response
{
    // Récupère la commande via son ID
    // en sql : SELECT * FROM `order` WHERE id = ?
    $order = $orderRepository->find($id);

    // dump($order);

    // Dans le champ "historical" du Order, on a du JSON qui stocke les IDs :
    // - id color
    // - id gender
    // - id size
    // - id variant
    // - id product
    // La fonction getOrderDetailDatas() permet, à partir de ces IDs,
    // de retrouver les entités correspondantes (Product, Variant, Color, Gender, Size)
    $datas = $this->orderService->getOrderDetailDatas($order);

    // dd($datas);

    // On envoie la commande et ses détails à la vue Twig
    return $this->render('admin_order/detail.html.twig', [
        'controller_name' => 'AdminOrderController',
        'order' => $order,
        'datas' => $datas,
    ]);
}



}
