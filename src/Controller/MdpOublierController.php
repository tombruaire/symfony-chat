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

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form["email"]->getData();
            $emailExist = $entityManager->getRepository(User::class)->checkEmail($email);

            if ($emailExist === 1) {
                // Redirection vers le formulaire de réinitialisation du mot de passe
                return $this->redirectToRoute('app_mdp_reset', ['email' => $email]);
            }
        }

        return $this->render('mdp_oublier/index.html.twig', [
            'controller_name' => 'MdpOublierController',
            'MdpOublierForm' => $form,
        ]);
    }
}
