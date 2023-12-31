<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Avis;
use App\Entity\Comments;
use App\Entity\Marques;
use App\Form\AuthorType;
use App\Form\AvisType;
use App\Form\CommentsType;
use App\Repository\AuthorRepository;
use App\Repository\MarquesRepository;
use App\Repository\AvisRepository;
use App\Repository\CommentsRepository;
use App\Repository\ResponsesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LogoRepository;

class avisController extends AbstractController {

    #[Route('/avis/{marque}', name: 'avis')]
    public function index(Request $request, EntityManagerInterface $em, MarquesRepository $marquesRepository, LogoRepository $logoRepository, $marque): Response {
        $author= new Author();
        $marque = $marquesRepository->find($marque);
        $logo = $logoRepository->findBy(['marque' => $marque]);
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($author);
            $em->flush();

            $authorId= $author->getId();

            return $this->redirect('https://127.0.0.1:8000/avis-next/'.$marque.'/'.$authorId);
        }
        return $this->render('avis/avis.html.twig', [
            'form' => $form->createView(),
            'logo' => $logo
        ]);
    }

    #[Route('/avis-next/{marque}/{author}', name: 'avis_next')]
    public function avisNext(Request $request, EntityManagerInterface $em, MarquesRepository $marquesRepository, AuthorRepository $authorRepository, LogoRepository $logoRepository, $marque, $author) {
        $marque_id = $marquesRepository->find($marque);
        $author_id = $authorRepository->find($author);
        $logo = $logoRepository->findBy(['marque' => $marque]);
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
            'marque' => $marque_id,
            'logo' => $logo
        ]);
    }

    #[Route('/marque/avis-list/{marque}/{user_log}', name: 'avis_list')]
    public function avisAll( AvisRepository $avisRepository, CommentsRepository $commentsRepository, MarquesRepository $marquesRepository, LogoRepository $logoRepository, $marque, $user_log) {

        $avis = $avisRepository->findBy(['marque' => $marque]);
        $marques = $marquesRepository->find($marque);
        $comments = $commentsRepository->findBy(['avis' => $avis]);
        $logo = $logoRepository->findBy(['marque' => $marque]);

            return $this->render('avis/avisAll.html.twig', [
            'avis' => $avis,
            'marque' => $marques,
            'comment' => $comments,
            'logo' => $logo,
            'user_log' => $user_log
        ]);
    }

    #[Route('/marque/avis-list/response/{avis_id}/{marque_id}', name: 'avis_comment')]
    public function response(Request $request, EntityManagerInterface $em, AvisRepository $avisRepository, MarquesRepository $marquesRepository, $avis_id, $marque_id) {
        $avis = $avisRepository->find($avis_id);
        $marques = $marquesRepository->find($marque_id);
        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAvis($avis);
            $comment->setMarque($marques);
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('avis/response.html.twig', [
            'avis' => $avis,
            'marque' => $marques,
            'form' => $form
        ]);
    }

    #[Route('/marque/avis-list/validate/{avis_id}/{marque_id}', name: 'avis_validation')]
    public function validation(Request $request, EntityManagerInterface $em, AvisRepository $avisRepository, MarquesRepository $marquesRepository, LogoRepository $logoRepository, $avis_id, $marque_id) {
        $avis = $avisRepository->find($avis_id);
        $marques = $marquesRepository->find($marque_id);
        $logo = $logoRepository->findBy(['marque' => $marques]);
        $avis->setEtat(1);
        $em->persist($avis);
        $em->flush();
        $avis = $avisRepository->findBy(['marque' => $marques]);
        return $this->render('avis/avisAll.html.twig', [
            'marque' => $marques,
            'avis' => $avis,
            'logo' => $logo
        ]);
    }

    #[Route('/marque/avis-list/delete/{avis_id}/{marque_id}', name: 'avis_delete')]
    public function delete(Request $request, EntityManagerInterface $em, AvisRepository $avisRepository, MarquesRepository $marquesRepository, LogoRepository $logoRepository, $avis_id, $marque_id) {
        $avis = $avisRepository->find($avis_id);
        $marques = $marquesRepository->find($marque_id);
        $logo = $logoRepository->findBy(['marque' => $marques]);
        $avis->setEtat(1);
        $em->remove($avis);
        $em->flush();
        $avis = $avisRepository->findBy(['marque' => $marques]);
        return $this->render('avis/avisAll.html.twig', [
            'marque' => $marques,
            'avis' => $avis,
            'logo' => $logo
        ]);
    }




}