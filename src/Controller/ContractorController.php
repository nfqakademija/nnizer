<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractorController extends AbstractController
{
    /**
     * @Route("/contractor", name="contractor")
     */
    public function index(): Response
    {
        return $this->render('contractor/index.html.twig', [
            'controller_name' => 'ContractorController',
        ]);
    }
}
