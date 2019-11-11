<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ClientRegistrationFormType;
use App\Service\MailerService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/new-client", name="app_client_register")
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param MailerService $mailer
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        TranslatorInterface $translator,
        MailerService $mailer
    ): Response {
        $reservation = new Reservation();
        $form = $this->createForm(ClientRegistrationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $reservation->setVerificationKey($reservation->generateActivationKey());
            $reservation->setVerificationKeyExpirationDate((new \DateTime('now'))->modify('+15 minutes'));
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->sendSuccessfulRegistrationEmail($reservation, $translator, $mailer);

            return $this->redirectToRoute('home');
        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param Reservation $reservation
     * @param TranslatorInterface $translatorInterface
     * @param MailerService $mailerService
     */
    public function sendSuccessfulRegistrationEmail(
        Reservation $reservation,
        TranslatorInterface $translatorInterface,
        MailerService $mailerService
    ): void {
        $mailerService->sendMail(
            $this->renderView(
                'emails/client-register.html.twig',
                [
                    'user' => $reservation->getFirstname(),
                    'key' => $reservation->getVerificationKey(),
                ]
            ),
            $translatorInterface->trans('mailer.title.registration'),
            $reservation->getEmail()
        );
    }

    /**
     * @Route("/activate/{verificationKey}", name="client_activate", methods="GET")
     * @param Request $request
     * @param string $verificationKey
     * @param TranslatorInterface $translator
     * @param MailerService $mailer
     * @return Response
     * @throws Exception
     */
    public function activate(
        Request $request,
        string $verificationKey,
        TranslatorInterface $translator,
        MailerService $mailer
    ): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'verificationKey' => $verificationKey,
        ]);

        if ($reservation != null && !$reservation->getIsVerified()) {
            $now = new \DateTime('now');
            if ($now < $reservation->getVerificationKeyExpirationDate()) {
                $reservation->setIsVerified(true);
                $entityManager->flush();
                $this->sendSuccessfulVerificationEmail($reservation, $translator, $mailer);
                $this->addFlash(
                    'notice',
                    $translator->trans('reservation.verified')
                );
            } else {
                $this->addFlash(
                    'notice',
                    $translator->trans('reservation.expired')
                );
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @param Reservation $reservation
     * @param TranslatorInterface $translatorInterface
     * @param MailerService $mailerService
     */
    private function sendSuccessfulVerificationEmail(
        Reservation $reservation,
        TranslatorInterface $translatorInterface,
        MailerService $mailerService
    ): void {
        $mailerService->sendMail(
            $this->renderView(
                'emails/client-verified.html.twig',
                [
                    'user' => $reservation->getFirstname(),
                    'key' => $reservation->getVerificationKey(),
                ]
            ),
            $translatorInterface->trans('mailer.title.registration'),
            $reservation->getEmail()
        );
    }

    /**
     * @Route("/cancel/{verificationKey}", name="client_cancel", methods="GET")
     * @param Request $request
     * @param string $verificationKey
     * @param TranslatorInterface $translator
     * @param MailerService $mailer
     * @return Response
     */
    public function cancel(
        Request $request,
        string $verificationKey,
        TranslatorInterface $translator,
        MailerService $mailer
    ): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'verificationKey' => $verificationKey,
        ]);

        if ($reservation != null && !$reservation->getIsCancelled()) {
            $reservation->setIsCancelled(true);
            $entityManager->flush();
            $this->sendSuccessfulCancellationEmail($reservation, $translator, $mailer);
            $this->addFlash(
                'notice',
                $translator->trans('reservation.cancelled')
            );
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @param Reservation $reservation
     * @param TranslatorInterface $translatorInterface
     * @param MailerService $mailerService
     */
    private function sendSuccessfulCancellationEmail(
        Reservation $reservation,
        TranslatorInterface $translatorInterface,
        MailerService $mailerService
    ): void {
        $mailerService->sendMail(
            $this->renderView(
                'emails/client-cancel.html.twig',
                ['user' => $reservation->getFirstname()]
            ),
            $translatorInterface->trans('mailer.title.registration'),
            $reservation->getEmail()
        );
    }
}
