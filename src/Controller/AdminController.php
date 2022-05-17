<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'app_admin')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $listeProduits=$produitRepository ->findAll();
        return $this->render('admin/index.html.twig', [
            'listeProduits' => $listeProduits,
        ]);
    }

    #[Route('/supprimer-produit/{id<\d+>}', name: 'deleteProduit')]
    public function deleteproduit(Produit $produit, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/modifierProduit/{id<\d+>}', name: 'modifyProduit')]
    public function modifyProduit(Produit $produit,Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $produit = $form->getData();
            //Gérer l'upload de l'image
            $image = $form->get('image')->getData();

            if($image){
                $newImageName = uniqid().".".$image->guessExtension();
                try{
                    $image->move(
                        $this->getParameter('repimage'),
                        $newImageName
                    );
                }
                catch(e){  }
                $produit->setImage($this->getParameter('prefixeimage').$newImageName);
            }
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash("success", "Votre produit a bien été créé");
            return $this->redirectToRoute("index");
        }
        return $this->render('produit/newProduit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nouveauProduit', name: 'newProduit')]
    public function newProduit(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $produit = $form->getData();
            //Gérer l'upload de l'image
            $image = $form->get('image')->getData();

            if($image){
                $newImageName = uniqid().".".$image->guessExtension();
                try{
                    $image->move(
                        $this->getParameter('repimage'),
                        $newImageName
                    );
                }
                catch(e){  }
                $produit->setImage($this->getParameter('prefixeimage').$newImageName);
            }
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash("success", "Votre produit a bien été créé");
            return $this->redirectToRoute("index");
        }
        return $this->render('produit/newProduit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
