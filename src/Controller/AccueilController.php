<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    #[Route('/accueil', name: 'index')]
    public function index(ProduitRepository $produitRepository): Response
    {

        $listeProduits = $produitRepository ->findAll();

        return $this->render('accueil/index.html.twig', [
            'listeProduits' => $listeProduits,
        ]);
    }
}
