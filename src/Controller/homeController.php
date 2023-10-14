<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AvisRepository;

class homeController extends AbstractController {

    #[Route('/{marque}', name: 'home')]

    public function index($marque, AvisRepository $avisRepository) {
        $avis = $avisRepository->findBy(['marque' => $marque]);

        return $this->render('homePage.html.twig', [
            'avis' => $avis
        ]);
    }
}