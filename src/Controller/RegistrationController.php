<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Form\RegistrationFormType;
use App\Security\ContractorAuthenticator;
use App\Service\MailerService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param ContractorAuthenticator $authenticator
     * @param MailerService $mailer
     * @param TranslatorInterface $translator
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        ContractorAuthenticator $authenticator,
        MailerService $mailer,
        TranslatorInterface $translator
    ): Response {
        $user = new Contractor();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setVerificationKey($user->generateVerificationKey());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->sendSignupEmail($user, $translator, $mailer);

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param Contractor $user
     * @param TranslatorInterface $translatorInterface
     * @param MailerService $mailerService
     */
    public function sendSignUpEmail(
        Contractor $user,
        TranslatorInterface $translatorInterface,
        MailerService $mailerService
    ): void {
        $mailerService->sendMail(
            $this->renderView(
                'emails/contractor-signup.html.twig',
                [
                    'user' => $user->getFirstname(),
                    'key' => $user->getVerificationKey()
                ]
            ),
            $translatorInterface->trans('email.heading.signup'),
            $user->getEmail()
        );
    }
}
