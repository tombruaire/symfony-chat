<?php

namespace App\Controller;

use App\Form\MdpOublierType;
use App\Form\NomUtilisateurOublierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NomUtilisateurOublierController extends AbstractController
{
    #[Route('/nom-utilisateur-oublier', name: 'app_nom_utilisateur_oublier', methods: ['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NomUtilisateurOublierType::class);
        $form->handleRequest($request);

        $alert = "";

        if ($form->isSubmitted() && $form->isValid()) {
            //
        }

        return $this->render('nom_utilisateur_oublier/index.html.twig', [
            'controller_name' => 'NomUtilisateurOublierController',
            'NomUtilisateurOublierForm' => $form,
            'alert' => $alert,
        ]);
    }
}
