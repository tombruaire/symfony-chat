<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MdpOublierType;
use App\Form\MdpResetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MdpOublierController extends AbstractController
{
    #[Route('/mdp-oublier', name: 'app_mdp_oublier', methods: ['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MdpOublierType::class);
        $form->handleRequest($request);

        $alert = "";

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'adresse email saisie dans l'input du formulaire
            $email = $form["email"]->getData();
            // Recherche de l'adresse email dans la base de données
            $emailExist = $entityManager->getRepository(User::class)->checkEmail($email);

            // Vérification si l'adresse email de l'utilisateur existe
            if ($emailExist === 1) { // Si l'adresse email existe
                // Récupération du Token associé à l'adresse email
                $token = $entityManager->getRepository(User::class)->getToken($email)["token"];
                // Redirection vers le formulaire de réinitialisation du mot de passe
                return $this->redirectToRoute('app_mdp_reset', ['email' => $email, 'token'=>$token]);
            } else { // Si l'adresse email n'existe pas
                // Affichage d'un message d'erreur
                $alert .= "Adresse email non trouvée !";
            }
        }

        return $this->render('mdp_oublier/index.html.twig', [
            'controller_name' => 'MdpOublierController',
            'MdpOublierForm' => $form,
            'alert' => $alert,
        ]);
    }
}
