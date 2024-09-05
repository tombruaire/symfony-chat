<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\User;
use App\Form\ChatType;
use App\Repository\MessagesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/chat', name: 'app_home')]
    #[IsGranted(new Expression('is_authenticated()'))]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Messages();
        $form = $this->createForm(ChatType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUserFrom($this->getUser());
            $message->setUserTo(null);

            // Création d'une nouvelle instance de DateTime
            $dateEnvoi = new \DateTime();
            // Ajout de 2 heures à la Date d'envoi
            $dateEnvoi->modify('+2 hours');
            // Assignement de la date modifiée à l'objet message
            $message->setDateEnvoi($dateEnvoi);

            $entityManager->persist($message);
            $entityManager->flush();
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $entityManager->getRepository(User::class)->findAll(),
            'ChatForm' => $form,
            'messages' => $entityManager->getRepository(Messages::class)->findAllMessages(),
        ]);
    }
}
