<?php

namespace App\Controller;

use App\Form\ModifierMdpType;
use App\Form\SupprimerCompteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    #[IsGranted(new Expression('is_authenticated()'))]
    public function index(Request $request): Response
    {
        $alert = "";

        /*** Modification du mot de passe ***/
        $formModifierMdp = $this->createForm(ModifierMdpType::class);
        $formModifierMdp->handleRequest($request);

        if ($formModifierMdp->isSubmitted() && $formModifierMdp->isValid()) {
            //
        }

        /*** Suppression du compte ***/
        $formSupprimerCompte = $this->createForm(SupprimerCompteType::class);
        $formSupprimerCompte->handleRequest($request);

        if ($formSupprimerCompte->isSubmitted() && $formSupprimerCompte->isValid()) {
            //
        }

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'alert' => $alert,
            'ModifierMdpForm' => $formModifierMdp,
            'SupprimerCompteForm' => $formSupprimerCompte,
        ]);
    }
}
