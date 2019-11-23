<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\Reservation;
use App\Form\ContractorSettingsType;
use App\Repository\ReservationRepository;
use App\Service\SerializerService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContractorController extends AbstractController
{
    /**
     * @Route("/contractor", name="contractor")
     * @return Response
     */
    public function index(): Response
    {
        $settings = $this->getDoctrine()
            ->getRepository(ContractorSettings::class)
            ->findOneBy(['contractor' => $this->getUser()->getId()
            ]);

        if ($settings === null) {
            return $this->redirectToRoute('contractor_settings');
        }

        return $this->render('contractor/index.html.twig', [
            'controller_name' => 'ContractorController',
        ]);
    }

    /**
     * @Route("/contractor/settings", name="contractor_settings")
     * @param Request $request
     * @return Response
     */
    public function settings(Request $request): Response
    {
        $settings = new ContractorSettings();
        $form = $this->createForm(ContractorSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contractor = $this->getDoctrine()
                ->getRepository(Contractor::class)
                ->findOneBy([
                    'id' => $this->getUser()->getId()
                ]);
            $settings->setContractor($contractor);
            $entityManager->persist($settings);
            $entityManager->flush();

            return $this->redirectToRoute('contractor');
        }

        return $this->render('contractor/settings.html.twig', [
            'settingsForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contractor/activate/{verificationKey}", name="contractor_activate", methods="GET")
     * @param string $verificationKey
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function activate(
        string $verificationKey,
        TranslatorInterface $translator
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
     * @Route("/api/contractor/{contractorKey}/get-clients/", methods="GET")
     * @param string $contractorKey
     * @param SerializerService $json
     * @return Response
     */
    public function getReservations(
        string $contractorKey,
        SerializerService $json
    ): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $contractor = $this->getContractorByKey($contractorKey);
        $reservations = $entityManager->getRepository(Reservation::class)->findBy([
            'contractor' => $contractor->getUsername(),
        ]);

        return new Jsonresponse($json->getResponse($reservations));
    }

    /**
     * @param string $key
     * @return Contractor
     */
    private function getContractorByKey(string $key): Contractor
    {
        return $this->getDoctrine()->getRepository(Contractor::class)
            ->findOneBy(
                [
                    'verificationKey' => $key
                ]
            );
    }
    /**
     * @Route("/api/contractor/{contractorKey}/cancel/{id}", methods="PATCH")
     * @param string $contractorKey
     * @param int $reservationId
     * @param MailerService $mailer
     * @return JsonResponse
     */
    public function cancelReservation(
        string $contractorKey,
        int $reservationId,
        MailerService $mailer
    ): JsonResponse {
        $entityManager = $this->getDoctrine()->getManager();
        $contractor = $this->getContractorByKey($contractorKey);
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'contractor' => $contractor->getUsername(),
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
     * @Route("/api/contractor/{contractorKey}/verify/{id}", methods="PATCH")
     * @param string $contractorKey
     * @param int $reservationId
     * @param MailerService $mailer
     * @return JsonResponse
     */
    public function verifyReservation(
        string $contractorKey,
        int $reservationId,
        MailerService $mailer
    ): JsonResponse {
        $entityManager= $this->getDoctrine()->getManager();
        $contractor = $this->getContractorByKey($contractorKey);
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'contractor' => $contractor->getUsername(),
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
     * @Route("/api/contractor/{contractorKey}/get-clients/{date}", methods="GET")
     * @param string $contractorKey
     * @param string $date
     * @param SerializerService $json
     * @param ReservationRepository $reservationsRepository
     * @return JsonResponse
     */
    public function getReservationsByDay(
        string $contractorKey,
        string $date,
        SerializerService $json,
        ReservationRepository $reservationsRepository
    ): JsonResponse {
        if ($this->validateDate($date, 'Y-m-d')) {
            $dateFrom = (\DateTime::createFromFormat('Y-n-d', $date))->setTime(0, 0, 0);
            $dateTo = (\DateTime::createFromFormat('Y-n-d', $date))->setTime(23, 59, 59);
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
        }
        $contractor = $this->getContractorByKey($contractorKey);
        $reservations = $reservationsRepository->findByDateInterval($contractor->getUsername(), $dateFrom, $dateTo);


        if ($reservations !== null) {
            return new Jsonresponse($json->getResponse($reservations));
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param string $date
     * @param string $format
     * @return bool
     */
    private function validateDate(string $date, string $format = 'Y-m-d H:i:s'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
