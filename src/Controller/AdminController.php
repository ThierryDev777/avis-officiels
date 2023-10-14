<?php

namespace App\Controller;

use App\Entity\Marques;
use App\Repository\MarquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(MarquesRepository $marquesRepository, EntityManagerInterface $em): Response
    {
        $marques = $marquesRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'marque' => $marques ,
        ]);
    }
}
