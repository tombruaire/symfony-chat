<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MdpResetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MdpResetController extends AbstractController
{
    #[Route('/mdp-oublier/{email}/{token}/reset-mdp', name: 'app_mdp_reset', methods: ['GET','POST'])]
    public function index($email, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(MdpResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Rénitialisation du mot de passe
            $password = $form["password"]->getData();
            $entityManager->getRepository(User::class)->resetPassword($email, $password, $userPasswordHasher);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('mdp_reset/index.html.twig', [
            'controller_name' => 'MdpResetController',
            'MdpResetForm' => $form,
            'email' => $email,
        ]);
    }
}
