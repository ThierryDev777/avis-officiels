<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AvisRepository;
use App\Repository\MarquesRepository;

class homeController extends AbstractController {

    #[Route('/{marque}', name: 'home')]

    public function index($marque, AvisRepository $avisRepository, MarquesRepository $marquesRepository) {
        $avis = $avisRepository->findBy(['marque' => $marque]);
        $marque = $marquesRepository->find($marque);

        return $this->render('homePage.html.twig', [
            'avis' => $avis,
            'marque' => $marque
        ]);
    }
}