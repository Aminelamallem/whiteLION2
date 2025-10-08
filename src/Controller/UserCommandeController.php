<?php

namespace App\Controller;


use App\Service\OrderService;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserCommandeController extends AbstractController
{

    public function __construct(private OrderService $OrderService) {
        
    }
     
     #[Route('/mes-commandes', name: 'app_user_commande')]
public function mesCommandes(OrderRepository $orderRepository ): Response
{
    // On récupère l'utilisateur connecté
    $user = $this->getUser();

   
   

    // On récupère uniquement les commandes de cet utilisateur
    // en sql : SELECT * FROM `order` WHERE user_id = ?
    $orders = $orderRepository->findBy(['user' => $user]);

    // dd($orders);

    return $this->render('user_commande/mes_commandes.html.twig', [
        'orders' => $orders,
    ]);
}




    #[Route('/mes-commandes/{id}', name: 'app_user_commande_detail')]
public function commandeDetail(int $id, OrderRepository $orderRepository): Response
{
    // On récupère l'utilisateur connecté
    $user = $this->getUser();

    

    // On récupère la commande par son ID
    $order = $orderRepository->find($id);

    

    // Vérifie que la commande appartient bien à l'utilisateur connecté
   

    // On transforme les données du champ historical en entités
    $datas = $this->OrderService->getOrderDetailDatas($order);

    // Envoi à la vue Twig
    return $this->render('user_commande/commande_detail.html.twig', [
        'order' => $order,
        'datas' => $datas,
    ]);
}


 

}
