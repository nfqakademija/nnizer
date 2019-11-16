<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\Reservation;
use App\Service\SerializerService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContractorController extends AbstractController
{
    /**
     * @Route("/contractor", name="contractor")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('contractor/index.html.twig', [
            'controller_name' => 'ContractorController',
        ]);
    }
    /**
     * @Route("/contractor/activate/{verificationKey}", name="contractor_activate", methods="GET")
     * @param Request $request
     * @param string $verificationKey
     * @param TranslatorInterface $translator
     * @param MailerService $mailer
     * @return Response
     */
    public function activate(
        Request $request,
        string $verificationKey,
        TranslatorInterface $translator,
        MailerService $mailer
    ): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Contractor::class)->findOneBy([
            'verificationKey' => $verificationKey,
        ]);

        if ($user != null && !$user->getIsVerified()) {
            $user->setIsVerified(true);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                $translator->trans('flash.signup.verified')
            );
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/api/contractor/{contractorUsername}/get-clients/", methods="GET")
     * @param string $contractorUsername
     * @param SerializerService $json
     * @return Response
     */
    public function getReservations(
        string $contractorUsername,
        SerializerService $json
    ): Response {

        $entityManager = $this->getDoctrine()->getManager();
        $reservations = $entityManager->getRepository(Reservation::class)->findBy([
            'contractor' => $contractorUsername,
        ]);

        return new Jsonresponse($json->getResponse($reservations));
    }

    /**
     * @Route("/api/contractor/{contractorUsername}/cancel/{id}", methods="PATCH")
     * @param string $contractorUsername
     * @param int $reservationId
     * @param MailerService $mailer
     * @return JsonResponse
     */
    public function cancelReservation(
        string $contractorUsername,
        int $reservationId,
        MailerService $mailer
    ): JsonResponse {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'contractor' => $contractorUsername,
            'id' => $reservationId,
            'isCancelled' => false,
        ]);
        if ($reservation !== null) {
            $reservation->setIsCancelled(true);
            $mailer->sendSuccessfulCancellationEmail($reservation);
            $entityManager->flush();

            return new JsonResponse();
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/contractor/{contractorUsername}/verify/{id}", methods="PATCH")
     * @param string $contractorUsername
     * @param int $reservationId
     * @param MailerService $mailer
     * @return JsonResponse
     */
    public function verifyReservation(
        string $contractorUsername,
        int $reservationId,
        MailerService $mailer
    ): JsonResponse {
        $entityManager= $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'contractor' => $contractorUsername,
            'id' => $reservationId,
            'isVerified' => false,
        ]);
        if ($reservation !== null) {
            $reservation->setIsVerified(true);
            $mailer->sendSuccessfulVerificationEmail($reservation);
            $entityManager->flush();

            return new JsonResponse();
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/contractor/{contractorUsername}/get-clients/{date}")
     * @param string $contractorUsername
     * @param string $date
     * @param SerializerService $json
     * @return JsonResponse
     */
    public function getReservationsByDay(
        string $contractorUsername,
        string $date,
        SerializerService $json
    ): JsonResponse {
        $dateFrom = (\DateTime::createFromFormat('Y-n-d', $date))->setTime(0, 0, 0);
        $dateTo = (\DateTime::createFromFormat('Y-n-d', $date))->setTime(23, 59, 59);

        $entityManager = $this->getDoctrine()->getManager();
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findByDateInterval($contractorUsername, $dateFrom, $dateTo);

        if ($reservations !== null) {
            return new Jsonresponse($json->getResponse($reservations));
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }
}
