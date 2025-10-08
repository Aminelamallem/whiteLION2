<?php

namespace App\Controller;

use App\Service\CartService;
use App\Form\ProductVariantCartType;
use App\Repository\GenderRepository;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/product')]
final class ArticleController extends AbstractController
{
    public function __construct(
        private CartService $cartService
    ) {}

  

    #[Route('variant/show/{id}', name: 'app_variant_show')]
    // cette route me permet de recuperer les donnes du formulaire dans ma session qui sapl panier 
    public function ShowProductVariant(
        ProductVariantRepository $productVariantRepository, 
        int $id, 
        Request $request, 
        SessionInterface $session
    ): Response {
        $productVariant = $productVariantRepository->find($id);

        if (!$productVariant) {
            throw $this->createNotFoundException('Variante de produit non trouvée');
        }
        // je cree le form avec la class ProductVariantCartType qui associer a aucune entite mais me permet de 
        // recuperer les donnes du formulaire
        $form = $this->createForm(ProductVariantCartType::class, null, [
            'productVariant' => $productVariant
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je recupere la session qui contient le panier
            $panier = $session->get('panier', []);
            
            // ici il va chercher dans cardservice la fonction addCard qui va prendre les parametre que je vais remplir ensuite ici
            $panier = $this->cartService->addToCart($productVariant, $form->getData(), $panier);
            // dd($panier);
            // ensuite je rempli ma session avec la valeur du panier 
            $session->set('panier', $panier);
    //   dd($panier);
               
            return $this->redirectToRoute('app_panier');
        }

        return $this->render('article/variant_show.html.twig', [
            'variant' => $productVariant,
            'product' => $productVariant->getProduct(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('variants/All', name: 'app_variant_list')]
    public function ListProductVariants(ProductVariantRepository $productVariantRepository): Response
    {
        $variants = $productVariantRepository->findAll();

        return $this->render('article/variants_list.html.twig', [
            'variants' => $variants,
        ]);
    }

    #[Route('/gender/{id}', name: 'app_gender_show')]
    public function show(
        int $id, 
        GenderRepository $genderRepository, 
        ProductVariantRepository $variantRepository
    ): Response {
        $gender = $genderRepository->find($id);

        if (!$gender) {
            throw $this->createNotFoundException("Genre introuvable");
        }
     

        
        $variants = $variantRepository->createQueryBuilder('v')
            ->join('v.genders', 'g')
            ->where('g.id = :genderId')
            ->setParameter('genderId', $id)
            ->getQuery()
            ->getResult();

        return $this->render('article/gender_show.html.twig', [
            'gender' => $gender,
            'variants' => $variants,
        ]);
    }
}
