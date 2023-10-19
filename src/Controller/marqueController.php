<?php

namespace App\Controller;

use App\Entity\Marques;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\MarquesType;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\MarquesRepository;

class marqueController extends AbstractController {

    #[Route('/marque/login', name: 'login_marque')]
    public function index(Request $request, MarquesRepository $marquesRepository) {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            $marque = $marquesRepository->findOneBy(['email' => $email]);
            if($marque && $marque->getPassword() == $password) {
                $marque_id = $marque->getId();
                $log = $marque->getName();
                return $this->redirect('/marque/avis-list/'.$marque_id.'/'.$log);
            }
        }
        return $this->render('marques/marqueLogin.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/', name: 'homepage')]
    public function homepage() {
        return $this->redirect('/marque/login');
    }

    #[Route('/inscription-marque/{inscription}', name: 'inscription_marque')]
    public function inscription(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response {

        $marques = new Marques();
        $form = $this->createForm(MarquesType::class, $marques);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($marques);
            $em->flush();

            $marque_id= $marques->getId();
            return $this->redirect('logo/'.$marque_id);
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