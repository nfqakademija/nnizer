<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use Exception;
use Swift_Mailer;
use Swift_SmtpTransport;
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
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        Swift_Mailer $mailer
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
            $this->sendActivationEmail($user, $mailer);

            return new RedirectResponse($this->generateUrl('home'));
        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param Client $client
     * @param Swift_Mailer $mailer
     */
    public function sendActivationEmail(Client $client, Swift_Mailer $mailer): void
    {
        $message = (new \Swift_Message('NNIZER reservation'))
            ->setFrom('nnizernfq@gmail.com')
            ->setTo($client->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/client-register.html.twig',
                    ['name' => $client->getFirstname()]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);

    }
}
