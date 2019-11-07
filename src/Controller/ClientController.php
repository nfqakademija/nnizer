<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/new-client", name="app_client_register")
     * @param Request $request
     * @return Response
     */
    public function register(
        Request $request
    ): Response {
        $user = new Client();
        $form = $this->createForm(ClientRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setVerificationKey($user->generateActivationKey());
            $user->setIsVerified(false);
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return new RedirectResponse($this->generateUrl('home'));

        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
