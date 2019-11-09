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
     * @var MailerService
     */
    private $mailer;
    /**
     * ClientController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, MailerService $mailer)
    {
        $this->translator = $translator;
        $this->mailer = $mailer;
    }
    /**
     * @Route("/new-client", name="app_client_register")
     * @param Request $request
     * @return Response
     * @throws Exception
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

            $this->sendSuccessfulRegistrationEmail($user->getFirstname(), $user->getEmail());

            return $this->redirectToRoute('home');
        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param string $firstname
     * @param string $email
     */
    private function sendSuccessfulRegistrationEmail(string $firstname, string $email): void
    {
        $this->mailer->sendMail(
            $this->renderView(
                'emails/client-register.html.twig',
                ['user' => $firstname]
            ),
            $this->translator->trans('mailer.title.registration'),
            $email
        );
    }
}
