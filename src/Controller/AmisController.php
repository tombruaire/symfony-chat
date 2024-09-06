<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AmisController extends AbstractController
{
    #[Route('/amis', name: 'app_amis')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $demandes = $entityManager->getRepository(Amis::class)->findDemandesCible($this->getUser()->getId());

        $userId = $this->getUser()->getId();

        $cibleId = "";
        foreach ($demandes as $demande) {
            if ($demande["cible"] == $userId) {
                $cibleId = $demande["cible"];
            }
            break;
        }

        $nbDemande = $entityManager->getRepository(Amis::class)->countDemandeAmi($this->getUser()->getId());

        return $this->render('amis/index.html.twig', [
            'controller_name' => 'AmisController',
            'demandes' => $demandes,
            'userId' => $userId,
            'cibleId' => $cibleId,
            'nbDemande' => $nbDemande,
        ]);
    }
}
