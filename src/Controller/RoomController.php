<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\User;
use App\Form\ChatType;
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

        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
            'users' => $entityManager->getRepository(User::class)->findAll(),
            'ChatForm' => $form,
            'messages' => $entityManager->getRepository(Messages::class)->findByMessagesUserTo(),
            'username' => $username,
        ]);
    }
}
