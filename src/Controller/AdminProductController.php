<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class AdminProductController extends AbstractController
{


    
     #[Route('/product/create', name: 'app_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {   
        $Product = new Product();
        $form = $this->createForm(ProductType::class, $Product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Product = $form->getData();
            $em->persist($Product);
            $em->flush();   
           
            return $this->redirectToRoute('app_show', ['id' => $Product ->getId()]);
        }
        return $this->render('admin_product/create.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
            
        ]);
    }


#[Route('/product/edit/{id}', name: 'app_edit_product')]
public function edit(int $id, EntityManagerInterface $em, Request $request, ProductRepository $productRepository): Response
{
    // $product = $em->getRepository(Product::class)->find($id);
    $product = $productRepository->find($id);

    if (!$product) {
        throw $this->createNotFoundException('Produit introuvable');
    }

    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('app_admin');
    }

    return $this->render('admin_product/edit.html.twig', [
        'controller_name' => 'ProductController',
        'form' => $form->createView(),
        'product' => $product
    ]);
}






     #[Route('/product/show/{id}', name: 'app_show')]
    public function show(ProductRepository $productRepository,$id): Response
    {

      $product = $productRepository->find($id);

if (!$product) {
    throw $this->createNotFoundException('Produit non trouvé');
}

        return $this->render('admin_product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product'=> $product
        ]);
    }

     #[Route('/product/list', name: 'app_showAll_admin')]
    public function Product(ProductRepository $productRepository): Response
    {
        $product = $productRepository->findAll();
        return $this->render('admin_product/list.html.twig', [
            'controller_name' => 'AdminController',
            'product' => $product,
        ]);
    }

   
   
   
 #[Route('/product/delete/{id}', name: 'app_delete-product')]
public function deleteProduct(Product $product, EntityManagerInterface $em): Response
{
    if (!$product) {
        throw $this->createNotFoundException('Produit non trouvé.');
    }

    // Supprimer toutes les variantes liées
    foreach ($product->getProductVariants() as $variant) {
        $em->remove($variant);
    }

    // Supprimer le produit
    $em->remove($product);
    $em->flush();

    return $this->redirectToRoute('app_admin');
}
   




}
