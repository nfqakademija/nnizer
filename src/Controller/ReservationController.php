<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\Reservation;
use App\Validator\ReservationValidation;
use App\Repository\ReservationRepository;
use App\Service\MailerService;
use App\Service\ReservationFactory;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/new-reservation", name="app_new-reservation")
     * @param Request $request
     * @param ReservationFactory $reservationFactory
     * @param MailerService $mailer
     * @param ReservationValidation $reservationValidation
     * @return JsonResponse
     * @throws Exception
     */
    public function register(
        Request $request,
        ReservationFactory $reservationFactory,
        MailerService $mailer,
        ReservationValidation $reservationValidation
    ): JsonResponse {
        $errors = $reservationValidation->validateInput($request);

        if (count($errors) === 0) {
            $email = $request->get('email');
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $contractor= $request->get('contractor');
            $visitDate = new \DateTime($request->get('visitDate'));
            $phoneNumber = $request->get('phoneNumber');

            $contractor = $this->getDoctrine()->getRepository(Contractor::class)->find($contractor);
            $reservations = $this->getDoctrine()->getRepository(Reservation::class)
                ->findConflictingReservations($contractor, $visitDate);

            if (count($reservations) > 0) {
                return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
            }

            $reservation = $reservationFactory
                ->createReservation($email, $firstname, $lastname, $visitDate, $phoneNumber);

            $reservation->setContractor($contractor);
            $this->getDoctrine()->getRepository(Reservation::class)->save($reservation);
            $mailer->sendSuccessfulRegistrationEmail($reservation);

            return new JsonResponse();
        } else {
            return new JsonResponse($errors, Response::HTTP_PARTIAL_CONTENT);
        }
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
