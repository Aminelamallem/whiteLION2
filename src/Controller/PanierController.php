<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')] // Accessible uniquement aux utilisateurs connectés
#[Route('/panier')]
final class PanierController extends AbstractController
{
    public function __construct(
        private CartService $cartService
    ) {}
#[Route('', name: 'app_panier')]
public function panier(Request $request): Response
{
    // On récupère la session de l'utilisateur
    $session = $request->getSession();

    // On récupère le panier stocké en session, ou un tableau vide si rien n’existe
    $cart = $session->get('panier', []); 

    // On transforme les IDs du panier en vraies entités (produits, couleurs, tailles, etc.)
    $cartUpToDate = $this->cartService->getEntitiesFromCart($cart);

    // On envoie les données au template Twig
    return $this->render('panier/index.html.twig', [
        'product' => $cartUpToDate['produits'],
        'total'   => $cartUpToDate['total'],
    ]);
}


  
    #[Route('/delete/{id}', name: 'app_panier_delete')]
    public function delete(int $id, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('panier', []);

        foreach ($cart as $key => $item) {
            if ($item['productVariantId'] === $id) {
                unset($cart[$key]);
            }
        }

        $session->set('panier', $cart);

        return $this->redirectToRoute('app_panier');
    }

   
    #[Route('/valider', name: 'app_cart_validate')]
    public function validateCart(
        SessionInterface $session,
        EntityManagerInterface $em,
        Security $security,
        Request $request
    ): Response {
        $cart = $session->get('panier', []);

        $order = new Order();
        $order->setOrderAt(new \DateTimeImmutable());
        $order->setUser($security->getUser());

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        //  ecoute le submit du form quirempli lentiter

        if ($form->isSubmitted() && $form->isValid()) {
            $cartUpToDate = $this->cartService->getEntitiesFromCart($cart);

            $total = 0;
            $historical = [];

            foreach ($cartUpToDate['produits'] as $item) {
                $total += $item['price'];
                $historical[] = [
                    'variant' => $item['variant']->getId(),
                    'color'   => $item['color']->getLabel(),
                    'gender'  => $item['gender']->getLabel(),
                    'size'    => $item['size']->getLabel(),
                    'price'   => $item['price'],
                ];
            }

            $order->setTotalPrice($total);
            $order->setHistorical($historical);

            $em->persist($order);
            // insert ou edit (UPDATE)
            $em->flush();

            $session->remove('panier');

            return $this->redirectToRoute('app_panier_success');
        }

        return $this->render('panier/validate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * 🎉 Page de confirmation après commande
     */
    #[Route('/success', name: 'app_panier_success')]
    public function success(): Response
    {
        return $this->render('panier/success.html.twig');
    }
}
