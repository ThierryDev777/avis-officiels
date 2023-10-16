<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Avis;
use App\Form\AuthorType;
use App\Form\AvisType;
use App\Repository\AuthorRepository;
use App\Repository\MarquesRepository;
use App\Repository\AvisRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class avisController extends AbstractController {

    #[Route('/avis/{marque}', name: 'avis')]
    public function index(Request $request, EntityManagerInterface $em, $marque): Response {
        $author= new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($author);
            $em->flush();

            $authorId= $author->getId();

            return $this->redirect('https://127.0.0.1:8000/avis-next/'.$marque.'/'.$authorId);
        }
        return $this->render('avis/avis.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/avis-next/{marque}/{author}', name: 'avis_next')]
    public function avisNext(Request $request, EntityManagerInterface $em, MarquesRepository $marquesRepository, AuthorRepository $authorRepository, $marque, $author) {
        $marque_id = $marquesRepository->find($marque);
        $author_id = $authorRepository->find($author);
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $avis->setAuthor($author_id);
            $avis->setMarque($marque_id);
            $avis->setDatetime(new \DateTime());
            $avis->setEtat(0);
            $em->persist($avis);
            $em->flush();

        }
        return $this->render('avis/avisNext.html.twig', [
            'form' => $form->createView(),
            'marque' => $marque_id
        ]);
    }

    #[Route('/marque/avis-list/{marque}', name: 'avis_list')]
    public function avisAll( AvisRepository $avisRepository, $marque) {

        $avis = $avisRepository->findBy(['marque' => $marque]);

            return $this->render('avis/avisAll.html.twig', [
            'avis' => $avis
        ]);
    }

    #[Route('/marque/avis-list/update/{avis_id}', name: 'avis_update')]
    public function update(Request $request, EntityManagerInterface $em, AvisRepository $avisRepository, $avis_id) {
        $avis = $avisRepository->find($avis_id);
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($avis);
            $em->flush();

        }

            return $this->render('avis/update.html.twig', [
            'avis' => $avis,
            'form' => $form
        ]);
    }

}