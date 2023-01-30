<?php

namespace App\Controller\ClientFCM;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', ['data' => 'dsd']);
    }
}