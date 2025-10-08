<?php

namespace App\Controller;

use App\Entity\ProductVariant;
use App\Form\ProductVariantType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin')]
final class AdminVariantProductController extends AbstractController
{
   


    

    #[Route('/product-variant/create/{id}', name: 'product_variant_create')]
    public function createProductVariant(
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        $id
    ): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $productVariant = new ProductVariant();
        
        $productVariant->setProduct($product);

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Déplace le fichier dans le dossier uploads/images
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                // Sauvegarde le nom du fichier dans l'entité
                $productVariant->setImage($newFilename);
            }

            $em->persist($productVariant);
            $em->flush();
            // il redirige vers la page de détail du produit auquel la variante a été ajoutée
            return $this->redirectToRoute('product_variant_show', ['id' => $productVariant ->getId()]);
        }
        return $this->render('admin_variant_product/create.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }



#[Route('/product-variant/edit/{id}', name: 'product_variant_edit')]
public function EditProductVariant(
    Request $request,
    ProductVariantRepository $productVariantRepository,
    EntityManagerInterface $em,
    int $id
): Response
{
    // Récupérer la variante existante
    $productVariant = $productVariantRepository->find($id);

    if (!$productVariant) {
        throw $this->createNotFoundException('Variante de produit non trouvée');
    }

    // Création du formulaire avec l'entité attachée
    $form = $this->createForm(ProductVariantType::class, $productVariant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer le fichier image si un nouveau fichier a été uploadé
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l’upload de l’image.');
            }

            // Mettre à jour l’entité
            $productVariant->setImage($newFilename);
        }

        // Enregistrer les autres modifications
        $em->flush();

        $this->addFlash('success', 'Variante mise à jour avec succès !');

        return $this->redirectToRoute('product_variant_show', [
            'id' => $productVariant->getId()
        ]);
    }

    return $this->render('admin_variant_product/edit.html.twig', [
        'form' => $form->createView(),
        'variant' => $productVariant,
        'product' => $productVariant->getProduct(),
    ]);
}



#[Route('/product-variant/show/{id}', name: 'product_variant_show')]
public function ShowProductVariant(ProductVariantRepository $productVariantRepository, $id): Response
{
    $productVariant = $productVariantRepository->find($id);

    if (!$productVariant) {
        throw $this->createNotFoundException('Variante de produit non trouvée');
    }

    return $this->render('admin_variant_product/show.html.twig', [
        'variant' => $productVariant,
        'product' => $productVariant->getProduct(),
    ]);
}

#[Route('/product-variants/All', name: 'product_variant_list')]
public function ListProductVariants(ProductVariantRepository $productVariantRepository): Response
{
    // Récupère toutes les variantes
    $variants = $productVariantRepository->findAll();

    return $this->render('admin_variant_product/list.html.twig', [
        'variants' => $variants,
    ]);
}





#[Route('/product-variant/delete/{id}', name: 'product_variant_delete')]
public function delete(EntityManagerInterface $em, ProductVariantRepository $productVariantRepository, int $id): Response
{
    $productVariant = $productVariantRepository->find($id);

    if ($productVariant) {
        $em->remove($productVariant);
        $em->flush();
    }

    return $this->redirectToRoute('product_variant_list');
}


}
