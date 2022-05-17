<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit/{id<\d+>}', name: 'app_produit')]
    public function index(Produit $produit): Response
    {
        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
        ]);
    }
}
