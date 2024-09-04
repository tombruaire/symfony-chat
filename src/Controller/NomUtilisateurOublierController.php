<?php

namespace App\Controller;

use App\Entity\User;
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

        $username = "";
        $alert = "";

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'adresse email saisie dans l'input du formulaire
            $email = $form["email"]->getData();
            // Recherche de l'adresse email dans la base de données
            $emailExist = $entityManager->getRepository(User::class)->checkEmail($email);

            // Vérification si l'adresse email de l'utilisateur existe
            if ($emailExist === 1) { // Si l'adresse email existe
                // Récupération du nom d'utilisateur
                $username .= $entityManager->getRepository(User::class)->getUsername($email)["username"];

            } else { // Si l'adresse email n'existe pas
                // Affichage d'un message d'erreur
                $alert .= "Aucun nom d'utilisateur associé à cette adresse email !";
            }
        }

        return $this->render('nom_utilisateur_oublier/index.html.twig', [
            'controller_name' => 'NomUtilisateurOublierController',
            'NomUtilisateurOublierForm' => $form,
            'alert' => $alert,
            'username' => $username,
        ]);
    }
}
