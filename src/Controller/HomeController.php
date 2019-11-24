<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/lang/{lang}", name="language")
     * @param Request $request
     * @param string $lang
     * @return Response
     */
    public function setLanguage(Request $request, string $lang): Response
    {
        if ($request->headers->get('referer') && $lang !== null) {
            $request->getSession()->set('_locale', $lang);
            return $this->redirect($request->headers->get('referer'));
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
