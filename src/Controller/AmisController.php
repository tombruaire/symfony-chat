<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use App\Form\AccepterDemandeAmisType;
use App\Form\AccepterRefuserDemandeAmisType;
use App\Form\RefuserDemandeAmisType;
use App\Form\TwoFAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AmisController extends AbstractController
{
    #[Route('/amis', name: 'app_amis')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();

        $demandes = $entityManager->getRepository(Amis::class)->findDemandesCible($this->getUser()->getId());
        $cibleId = "";
        $demandeurId = "";
        $demandeStatut = "";
        foreach ($demandes as $demande) {
            if ($demande["cible_id"] == $userId) {
                $cibleId = $demande["cible_id"];
                $demandeurId = $demande["demandeur_id"];
                $demandeStatut = $demande["demande"];
            }
            break;
        }

        $nbDemande = $entityManager->getRepository(Amis::class)->countDemandeAmi($this->getUser()->getId());

        /*** Gestion acceptation demandes amis ***/
        $amis = new Amis();
        $formAcceptationDemandeAmis = $this->createForm(AccepterDemandeAmisType::class, $amis);
        $formAcceptationDemandeAmis->handleRequest($request);

        if ($formAcceptationDemandeAmis->isSubmitted() && $formAcceptationDemandeAmis->isValid()) {
            $entityManager->getRepository(Amis::class)->acceptDemande($demandeurId, $this->getUser()->getId());
            return $this->redirectToRoute('app_amis');
        }

        /*** Gestion refus demandes amis ***/
        $formRefusDemandeAmis = $this->createForm(RefuserDemandeAmisType::class);
        $formRefusDemandeAmis->handleRequest($request);

        if ($formRefusDemandeAmis->isSubmitted() && $formRefusDemandeAmis->isValid()) {
            //
        }

        return $this->render('amis/index.html.twig', [
            'controller_name' => 'AmisController',
            'demandes' => $demandes,
            'userId' => $userId,
            'cibleId' => $cibleId,
            'nbDemande' => $nbDemande,
            'formAcceptationDemandeAmis' => $formAcceptationDemandeAmis,
            'formRefusDemandeAmis' => $formRefusDemandeAmis,
            'demandeStatut' => $demandeStatut,
            'amis' => $entityManager->getRepository(Amis::class)->findAllAmis($this->getUser()->getId()),
        ]);
    }
}
