<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use App\Service\MailerService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ClientController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ClientController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    /**
     * @Route("/new-client", name="app_client_register")
     * @param Request $request
     * @param MailerService $mailer
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        MailerService $mailer
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
            $renderedTemplate = $this->renderView(
                'emails/client-register.html.twig',
                ['user' => $user->getFirstname()]
            );
            $mailer->sendMail(
                $renderedTemplate,
                $this->translator->trans('mailer.title.registration'),
                $user->getEmail()
            );

            return new RedirectResponse($this->generateUrl('home'));
        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
