<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ClientRegistrationFormType;
use App\Repository\ReservationRepository;
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
     * @param MailerService $mailer
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        MailerService $mailer
    ): Response {
        $reservation = new Reservation();
        $form = $this->createForm(ClientRegistrationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setVisitDate(new \DateTime($request->get('visitDate')));
            $reservation->setVerificationKey($reservation->generateActivationKey());
            $reservation->setVerificationKeyExpirationDate((new \DateTime('now'))->modify('+15 minutes'));
            $this->getDoctrine()->getRepository(Reservation::class)->save($reservation);
            $mailer->sendSuccessfulRegistrationEmail($reservation);

            return $this->redirectToRoute('home');
        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activate/{verificationKey}", name="client_activate", methods="GET")
     * @param string $verificationKey
     * @param TranslatorInterface $translator
     * @param MailerService $mailer
     * @param ReservationRepository $reservationRepository
     * @return Response
     * @throws Exception
     */
    public function activate(
        string $verificationKey,
        TranslatorInterface $translator,
        MailerService $mailer,
        ReservationRepository $reservationRepository
    ): Response {
        $reservation = $reservationRepository->findOneBy([
            'verificationKey' => $verificationKey
        ]);

        if ($reservation != null && !$reservation->getIsVerified()) {
            $now = new \DateTime('now');
            if ($now < $reservation->getVerificationKeyExpirationDate()) {
                $reservation->setIsVerified(true);
                $reservationRepository->save($reservation);
                $mailer->sendSuccessfulVerificationEmail($reservation);
                $this->addFlash(
                    'notice',
                    $translator->trans('flash.reservation.verified')
                );
            } else {
                $this->addFlash(
                    'notice',
                    $translator->trans('flash.reservation.expired')
                );
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/cancel/{verificationKey}", name="client_cancel", methods="GET")
     * @param string $verificationKey
     * @param TranslatorInterface $translator
     * @param MailerService $mailer
     * @param ReservationRepository $reservationRepository
     * @return Response
     */
    public function cancel(
        string $verificationKey,
        TranslatorInterface $translator,
        MailerService $mailer,
        ReservationRepository $reservationRepository
    ): Response {
        $reservation = $reservationRepository->findOneBy([
            'verificationKey' => $verificationKey,
        ]);

        if ($reservation != null && !$reservation->getIsCancelled()) {
            $reservation->setIsCancelled(true);
            $reservationRepository->save($reservation);
            $mailer->sendSuccessfulCancellationEmail($reservation);
            $this->addFlash(
                'notice',
                $translator->trans('flash.reservation.cancelled')
            );
        }

        return $this->redirectToRoute('home');
    }
}
