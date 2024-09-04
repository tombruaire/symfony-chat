<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérification si le Nom d'utilisateur est déjà utilisé
            $username = $form["username"]->getData();
            $usernameExist = $entityManager->getRepository(User::class)->checkUsername($username);

            // Vérification si l'Adresse email est déjà utilisée
            $email = $form["email"]->getData();
            $emailExist = $entityManager->getRepository(User::class)->checkEmail($email);

            // Si le Nom d'utilisateur et l'Adresse email ne sont pas utilisés
            if ($usernameExist === 0 && $emailExist === 0) {
                // Hachage du Mot de passe
                $password = $form["password"]->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $password));

                // Définition de la Date et heure d'inscription
                $user->setDateCreation(new \DateTime());

                // Génération d'un token de 20 caractères
                function generateToken(): string
                {
                    $chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                    $token = "";

                    // 5 lettres majuscules
                    $token .= $chaine[rand(0,25)];
                    $token .= $chaine[rand(0,25)];
                    $token .= $chaine[rand(0,25)];
                    $token .= $chaine[rand(0,25)];
                    $token .= $chaine[rand(0,25)];

                    // 5 lettres minuscules
                    $token .= $chaine[rand(26,51)];
                    $token .= $chaine[rand(26,51)];
                    $token .= $chaine[rand(26,51)];
                    $token .= $chaine[rand(26,51)];
                    $token .= $chaine[rand(26,51)];

                    // 10 chiffres
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];
                    $token .= $chaine[rand(52,60)];

                    $token = str_shuffle($token);

                    return $token;
                }
                $user->setToken(generateToken());

                // Ajout du nouvel utilisateur dans la base de données
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirection vers la page de connexion
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
