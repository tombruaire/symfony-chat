<?php

namespace App\Controller;

use App\Entity\Amis;
use App\Entity\Messages;
use App\Entity\User;
use App\Form\AnnulerDemandeAmiType;
use App\Form\ChatType;
use App\Form\DemandeAmiType;
use App\Repository\AmisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/room/{username}', name: 'app_room')]
    public function index($username, Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Messages();
        $form = $this->createForm(ChatType::class, $message);
        $form->handleRequest($request);

        $userTo = $entityManager->getRepository(User::class)->findUserTo($username);

        // Récupération de l'id du demandeur
        $idDemandeur = $this->getUser()->getId();
        // Récupération de l'id de l'ami (la cible)
        $idCible = $userTo->getId();

        $usernamesCible = $entityManager->getRepository(Amis::class)->findDemandesCible($idCible);
        $idUserCible = "";
        $idUserCibleAmis = "";
        $usernamesCibleAmis = "";
        foreach ($usernamesCible as $usernameCible) {
            $idUserCible .= $usernameCible["demandeur_id"];
            $idUserCibleAmis .= $usernameCible["cible_id"];
            $usernamesCibleAmis .= $usernameCible["cible"];
            break;
        }

        $statutDemandeAmi = null;
        if ($entityManager->getRepository(Amis::class)->findByCheckStatut($idDemandeur, $idCible)) {
            $statutDemandeAmi = $entityManager->getRepository(Amis::class)->findByCheckStatut($idDemandeur, $idCible)[0]["demande"];
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUserFrom($this->getUser());
            $message->setUserTo($userTo);

            // Création d'une nouvelle instance de DateTime
            $dateEnvoi = new \DateTime();
            // Ajout de 2 heures à la Date d'envoi
            $dateEnvoi->modify('+2 hours');
            // Assignement de la date modifiée à l'objet message
            $message->setDateEnvoi($dateEnvoi);

            $entityManager->persist($message);
            $entityManager->flush();
        }

        /*** Ajout d'ami ***/
        $amis = new Amis();
        $formDemandeAmi = $this->createForm(DemandeAmiType::class, $amis);
        $formDemandeAmi->handleRequest($request);

        if ($formDemandeAmi->isSubmitted() && $formDemandeAmi->isValid()) {
            // Définition des valeurs
            $amis->setDemandeur($idDemandeur);
            $amis->setCible($idCible);
            $amis->setDemande("envoye");

            // Ajout dans la base de données
            $entityManager->persist($amis);
            $entityManager->flush();

            return $this->redirectToRoute('app_room', ['username' => $username]);
        }

        /*** Annulation demande amis ***/
        $formAnnulerDemandeAmi = $this->createForm(AnnulerDemandeAmiType::class);
        $formAnnulerDemandeAmi->handleRequest($request);

        if ($formAnnulerDemandeAmi->isSubmitted() && $formAnnulerDemandeAmi->isValid()) {
            $entityManager->getRepository(Amis::class)->annulerDemandeAmi($idDemandeur, $idCible);
            // Suppression dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_room', ['username' => $username]);
        }

        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
            'users' => $entityManager->getRepository(User::class)->findAll(),
            'ChatForm' => $form,
            'messages' => $entityManager->getRepository(Messages::class)->findByMessagesUserTo(),
            'username' => $username,
            'formDemandeAmi' => $formDemandeAmi,
            'formAnnulerDemandeAmi' => $formAnnulerDemandeAmi,
            'statutDemandeAmi' => $statutDemandeAmi,
            'idUserCible' => $idUserCible,
            'idUserCibleAmis' => $idUserCibleAmis,
            'usernamesCibleAmis' => $usernamesCibleAmis,
        ]);
    }
}
