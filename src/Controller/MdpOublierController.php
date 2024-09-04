<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MdpOublierController extends AbstractController
{
    #[Route('/mdp-oublier', name: 'app_mdp_oublier')]
    public function index(): Response
    {
        return $this->render('mdp_oublier/index.html.twig', [
            'controller_name' => 'MdpOublierController',
        ]);
    }
}
