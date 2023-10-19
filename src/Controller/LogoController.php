<?php

namespace App\Controller;

use App\Entity\Logo;
use App\Form\LogoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\MarquesRepository;

class LogoController extends AbstractController
{
    #[Route('/inscription-marque/logo/{marque_id}', name: 'app_logo')]
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger,  MarquesRepository $marquesRepository, $marque_id): Response
    {
        $logos = new Logo();
        $marque = $marquesRepository->find($marque_id);
        $form = $this->createForm(LogoType::class, $logos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logo = $form->get('name')->getData();
            if ($logo) {
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logo->guessExtension();
                try {
                    $logo->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $logos->setName($newFilename);
                $logos->setMarque($marque);
                $em->persist($logos);
                $em->flush();
                return $this->redirect('https://127.0.0.1:8000/marque-inscription/api/'.$marque_id);
            }

        }
        return $this->render('logo/index.html.twig', [
            'form' => $form,
        ]);
    }
}
