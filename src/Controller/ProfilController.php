<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifierMdpType;
use App\Form\SupprimerCompteType;
use App\Form\TwoFADesactiverType;
use App\Form\TwoFAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    #[IsGranted(new Expression('is_authenticated()'))]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $alertDanger = "";
        $alertSuccess = "";

        $alertActivate2FA = "";
        $alertDesactivate2FA = "";

        /*** Activation de la Double Authentification ***/
        $formActiver2FA = $this->createForm(TwoFAType::class);
        $formActiver2FA->handleRequest($request);

        if ($formActiver2FA->isSubmitted() && $formActiver2FA->isValid()) {
            $entityManager->getRepository(User::class)->activer2FA($this->getUser());
            $alertActivate2FA .= "Double authentification activée !";
        }

        /*** Désactivation de la Double Authentification ***/
        $formDesactiver2FA = $this->createForm(TwoFADesactiverType::class);
        $formDesactiver2FA->handleRequest($request);

        if ($formDesactiver2FA->isSubmitted() && $formDesactiver2FA->isValid()) {
            $entityManager->getRepository(User::class)->desactiver2FA($this->getUser());
            $alertDesactivate2FA .= "Double authentification désactivée !";
        }

        /*** Modification du mot de passe ***/
        $formModifierMdp = $this->createForm(ModifierMdpType::class);
        $formModifierMdp->handleRequest($request);

        if ($formModifierMdp->isSubmitted() && $formModifierMdp->isValid()) {
            // Modification du mot de passe dans la base de données
            $email = $this->getUser()->getEmail();
            $password = $formModifierMdp["password"]->getData();
            $entityManager->getRepository(User::class)->resetPassword($email, $password, $userPasswordHasher);

            // Déconnexion de l'utilisateur
            $session = new Session();
            $session->invalidate();

            // Redirection vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        /*** Suppression du compte ***/
        $formSupprimerCompte = $this->createForm(SupprimerCompteType::class);
        $formSupprimerCompte->handleRequest($request);

        if ($formSupprimerCompte->isSubmitted() && $formSupprimerCompte->isValid()) {
            //
        }

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'alertDanger' => $alertDanger,
            'alertSuccess' => $alertSuccess,
            'alertActivate2FA' => $alertActivate2FA,
            'alertDesactivate2FA' => $alertDesactivate2FA,
            'Activer2FAForm' => $formActiver2FA,
            'Desactiver2FAForm' => $formDesactiver2FA,
            'ModifierMdpForm' => $formModifierMdp,
            'SupprimerCompteForm' => $formSupprimerCompte,
        ]);
    }
}
