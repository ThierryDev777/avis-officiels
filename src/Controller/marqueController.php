<?php

namespace App\Controller;

use App\Entity\Marques;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\MarquesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class marqueController extends AbstractController {

    #[Route('/login-marque', name: 'login_marque')]
    public function index() {

        return $this->render('marques/marqueLogin.html.twig', []);
    }

    #[Route('/inscription-marque/{inscription}', name: 'inscription_marque')]
    public function inscription(Request $request, EntityManagerInterface $em): Response {

        $marques = new Marques();
        $form = $this->createForm(MarquesType::class, $marques);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($marques);
            $em->flush();

            $marque_id= $marques->getId();
            return $this->redirect('https://127.0.0.1:8000/marque-inscription/api/'.$marque_id);
        }

        return $this->render('marques/marqueInscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/marque-inscription/api/{marque}', name: 'marque_api')]
    public function api($marque) {

        return $this->render('marques/api.html.twig', [
            'marque' => $marque
        ]);
    }
}