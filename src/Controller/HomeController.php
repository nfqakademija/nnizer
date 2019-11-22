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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/lang/{lang}", name="language")
     * @param Request $request
     * @param $lang
     * @return Response
     */
    public function setLanguage(Request $request, $lang): Response
    {
        if ($request->headers->get('referer') && $lang !== null) {
            $request->getSession()->set('_locale', $lang);
            
            return $this->redirect($request->headers->get('referer'));
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
