<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\Reservation;
use App\Form\ContractorSettingsType;
use App\Repository\ContractorRepository;
use App\Repository\ContractorSettingsRepository;
use App\Repository\ReservationRepository;
use App\Service\ContractorService;
use App\Service\ReservationFactory;
use App\Service\SerializerService;
use App\Service\MailerService;
use Exception;
use Doctrine\ORM\NonUniqueResultException;
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
     * @param ContractorSettingsRepository $contractorSettingsRepository
     * @return Response
     */
    public function index(ContractorSettingsRepository $contractorSettingsRepository): Response
    {
        $settings = $contractorSettingsRepository->findOneBy(['contractor' => $this->getUser()->getId()]);

        if ($settings === null) {
            return $this->redirectToRoute('contractor_settings');
        }

        return $this->render('contractor/index.html.twig', [
            'controller_name' => 'ContractorController',
        ]);
    }

    /**
     * @Route("/c/{contractorUsername}", name="contractor-page")
     * @param string $contractorUsername
     * @param ContractorRepository $contractorRepository
     * @return Response
     */
    public function contractorPage(
        string $contractorUsername,
        ContractorRepository $contractorRepository
    ): Response {
        $contractor = $contractorRepository->findOneBy(['username' => $contractorUsername]);

        if ($contractor !== null) {
            return $this->render('contractor/page.html.twig', [
                'contractor'=> $contractor,
                'errors' => [], //TODO
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/contractor/settings", name="contractor_settings")
     * @param Request $request
     * @param ContractorRepository $contractorRepository
     * @return Response
     */
    public function settings(Request $request, ContractorRepository $contractorRepository): Response
    {
        $settings = new ContractorSettings();
        $form = $this->createForm(ContractorSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractor = $contractorRepository->findOneBy([
                'id' => $this->getUser()->getId()
            ]);
            $settings->setContractor($contractor);
            $this->getDoctrine()->getRepository(ContractorSettings::class)->save($settings);

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
     * @param ContractorRepository $contractorRepository
     * @return Response
     */
    public function activate(
        string $verificationKey,
        TranslatorInterface $translator,
        ContractorRepository $contractorRepository
    ): Response {
        $user = $contractorRepository->findOneBy([
            'verificationKey' => $verificationKey,
        ]);

        if ($user != null && !$user->getIsVerified()) {
            $user->setIsVerified(true);
            $contractorRepository->save($user);
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
     * @param ContractorRepository $contractorRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getReservations(
        string $contractorKey,
        SerializerService $json,
        ContractorRepository $contractorRepository
    ): Response {
        $contractor = $contractorRepository->findOneByKey($contractorKey);
        $reservations = $contractor->getReservations();

        return new Jsonresponse($json->getResponse($reservations));
    }

    /**
     * @Route("/api/contractor/{contractorKey}/cancel/{reservationId}", methods="GET")
     * @param string $contractorKey
     * @param int $reservationId
     * @param MailerService $mailer
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function cancelReservation(
        string $contractorKey,
        int $reservationId,
        MailerService $mailer,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository
    ): JsonResponse {
        $contractor = $contractorRepository->findOneByKey($contractorKey);
        $reservation = $reservationRepository->findOneBy([
            'contractor' => $contractor,
            'id' => $reservationId,
            'isCancelled' => false,
        ]);

        if ($reservation !== null) {
            $reservation->setIsCancelled(true);
            $reservationRepository->save($reservation);
            $mailer->sendSuccessfulCancellationEmail($reservation);

            return new JsonResponse();
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/contractor/{contractorKey}/verify/{reservationId}", methods="GET")
     * @param string $contractorKey
     * @param int $reservationId
     * @param MailerService $mailer
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function verifyReservation(
        string $contractorKey,
        int $reservationId,
        MailerService $mailer,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository
    ): JsonResponse {
        $contractor = $contractorRepository->findOneByKey($contractorKey);
        $reservation = $reservationRepository->findOneBy([
            'contractor' => $contractor,
            'id' => $reservationId,
            'isVerified' => false,
        ]);

        if ($reservation !== null) {
            $reservation->setIsVerified(true);
            $reservationRepository->save($reservation);
            $mailer->sendSuccessfulVerificationEmail($reservation);

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
     * @param ContractorRepository $contractorRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getReservationsByDay(
        string $contractorKey,
        string $date,
        SerializerService $json,
        ReservationRepository $reservationsRepository,
        ContractorRepository $contractorRepository
    ): JsonResponse {
        if ($this->validateDate($date, 'Y-m-d')) {
            $dateFrom = (\DateTime::createFromFormat('Y-n-d', $date))->setTime(0, 0, 0);
            $dateTo = (\DateTime::createFromFormat('Y-n-d', $date))->setTime(23, 59, 59);
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
        }
        $contractor = $contractorRepository->findOneByKey($contractorKey);
        $reservations = $reservationsRepository->findByDateInterval($contractor, $dateFrom, $dateTo);

        if ($reservations !== null) {
            return new Jsonresponse($json->getResponse($reservations));
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/contractor/{contractorKey}/new-client", methods="POST")
     * @param Request $request
     * @param string $contractorKey
     * @param ReservationFactory $reservationFactory
     * @return JsonResponse
     * @throws Exception
     */
    public function addReservation(
        Request $request,
        string $contractorKey,
        ReservationFactory $reservationFactory
    ): JsonResponse {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $visitDate = new \DateTime($request->get('visitDate'));

        if (!$firstname || !$lastname || !$email || !$visitDate) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $reservation = $reservationFactory->createReservation(
            $email,
            $firstname,
            $lastname,
            $visitDate
        );
        $contractor = $this->getDoctrine()->getRepository(Contractor::class)
            ->findOneByKey($contractorKey);
        $reservation->setContractor($contractor);
        $this->getDoctrine()->getRepository(Reservation::class)->save($reservation);

        return new JsonResponse();
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

    /**
     * @Route("/api/profile/{contractorUsername}/working-hours", methods="GET")
     * @param string $contractorUsername
     * @param ContractorRepository $contractorRepository
     * @param ContractorService $contractorService
     * @return JsonResponse
     */
    public function getWorkingHoursAndTakenDates(
        string $contractorUsername,
        ContractorRepository $contractorRepository,
        ContractorService $contractorService
    ): JsonResponse {
        $contractor = $contractorRepository->findOneBy(['username' => $contractorUsername]);
        $response = $contractorService->generateContractorCalenderResponse($contractor);
        if ($response) {
            return new JsonResponse($response);
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

}
