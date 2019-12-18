<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\Reservation;
use App\Form\ContractorDetailsFormType;
use App\Form\ContractorSettingsType;
use App\Repository\ContractorRepository;
use App\Repository\ContractorSettingsRepository;
use App\Repository\ReservationRepository;
use App\Security\ContractorAuthenticator;
use App\Service\ContractorService;
use App\Service\ReservationFactory;
use App\Service\SerializerService;
use App\Service\MailerService;
use App\Validator\ReservationValidator;
use Doctrine\ORM\ORMException;
use Exception;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
            $this->addFlash('notice', 'detailsForm.missing');
            return $this->redirectToRoute('contractor_settings');
        }

        return $this->render('contractor/index.html.twig', [
            'controller_name' => 'ContractorController',
        ]);
    }

    /**
     * @Route("/service/{contractorUsername}", name="contractor-page")
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
                'contractor' => $contractor,
                'errors' => [], //TODO
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/contractor/edit", name="contractor_settings")
     * @param Request $request
     * @param ContractorRepository $contractorRepository
     * @param ContractorSettingsRepository $contractorSettingsRepository
     * @return Response
     * @throws ORMException
     */
    public function settings(
        Request $request,
        ContractorRepository $contractorRepository,
        ContractorSettingsRepository $contractorSettingsRepository
    ): Response {
        $contractor = $contractorRepository->findOneBy([
            'id' => $this->getUser()->getId()
        ]);
        $settings = $contractor->getSettings() ?? new ContractorSettings();
        $settingsForm = $this->createForm(ContractorSettingsType::class, $settings);
        $settingsForm->handleRequest($request);

        if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
            $settings->setContractor($contractor);
            $this->getDoctrine()->getRepository(ContractorSettings::class)->save($settings);
            $this->addFlash('notice', 'settings.submitted');

            return $this->redirectToRoute('contractor_settings');
        }

        $detailsForm = $this->createForm(ContractorDetailsFormType::class, $contractor);
        $detailsForm->handleRequest($request);
        if ($detailsForm->isSubmitted() && $detailsForm->isValid()) {
            $contractorRepository->save($contractor);

            return $this->redirectToRoute('contractor');
        }

        return $this->render('contractor/settings.html.twig', [
            'settingsForm' => $settingsForm->createView(),
            'detailsForm' => $detailsForm->createView()
        ]);
    }

    /**
     * @Route("/contractor/activate/{verificationKey}", name="contractor_activate", methods="GET")
     * @param string $verificationKey
     * @param Request $request
     * @param GuardAuthenticatorHandler $guardHandler
     * @param ContractorAuthenticator $authenticator
     * @param ContractorRepository $contractorRepository
     * @return Response
     * @throws ORMException
     */
    public function activate(
        string $verificationKey,
        Request $request,
        GuardAuthenticatorHandler $guardHandler,
        ContractorAuthenticator $authenticator,
        ContractorRepository $contractorRepository
    ): Response {
        $user = $contractorRepository->findOneBy([
            'verificationKey' => $verificationKey,
        ]);

        if ($user != null && !$user->getIsVerified()) {
            $user->setIsVerified(true);
            $contractorRepository->save($user);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/api/contractor/{contractorKey}/get-clients/", methods="GET")
     * @param string $contractorKey
     * @param SerializerService $json
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @return Response
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function getReservations(
        string $contractorKey,
        SerializerService $json,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository
    ): Response {
        $contractor = $contractorRepository->findOneByKey($contractorKey);
        if ($contractor) {
            $reservations = $reservationRepository->findBy([
                'contractor' => $contractor,
                'isDeleted' => null
            ]);

            return new JsonResponse($json->getResponse($reservations));
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/contractor/{contractorKey}/cancel/{reservationId}", methods="PATCH")
     * @param string $contractorKey
     * @param int $reservationId
     * @param MailerService $mailer
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function cancelReservation(
        string $contractorKey,
        int $reservationId,
        MailerService $mailer,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository
    ): JsonResponse {
        $contractor = $contractorRepository->findOneByKey($contractorKey);

        if ($contractor) {
            $reservation = $reservationRepository->findOneBy([
                'contractor' => $contractor,
                'id' => $reservationId,
                'isCancelled' => false,
            ]);

            if ($reservation) {
                $reservation->setIsCancelled(true);
                $reservationRepository->save($reservation);
                $mailer->sendSuccessfulCancellationEmail($reservation);

                return new JsonResponse();
            }
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/contractor/{contractorKey}/verify/{reservationId}", methods="PATCH")
     * @param string $contractorKey
     * @param int $reservationId
     * @param MailerService $mailer
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function verifyReservation(
        string $contractorKey,
        int $reservationId,
        MailerService $mailer,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository
    ): JsonResponse {
        $contractor = $contractorRepository->findOneByKey($contractorKey);

        if ($contractor) {
            $reservation = $reservationRepository->findOneBy([
                'contractor' => $contractor,
                'id' => $reservationId,
                'isVerified' => false,
            ]);

            if ($reservation) {
                $reservation->setIsVerified(true);
                $reservationRepository->save($reservation);
                $mailer->sendSuccessfulVerificationEmail($reservation);

                return new JsonResponse();
            }
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/contractor/{contractorKey}/delete/{reservationId}", methods="DELETE")
     * @param string $contractorKey
     * @param int $reservationId
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function deleteReservation(
        string $contractorKey,
        int $reservationId,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository
    ): JsonResponse {
        $contractor = $contractorRepository->findOneByKey($contractorKey);

        if ($contractor) {
            $reservation = $reservationRepository->findOneBy([
                'contractor' => $contractor,
                'id' => $reservationId
            ]);

            if ($reservation) {
                $em = $this->getDoctrine()->getManager();
                $reservation->setIsDeleted(true);
                $em->persist($reservation);
                $em->flush();

                return new JsonResponse();
            }
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
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
     * @throws ExceptionInterface
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

        if ($contractor) {
            $reservations = $reservationsRepository->findByDateInterval($contractor, $dateFrom, $dateTo);
            if ($reservations) {
                return new Jsonresponse($json->getResponse($reservations));
            }
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/contractor/{contractorKey}/new-client", methods="POST")
     * @param Request $request
     * @param string $contractorKey
     * @param ReservationFactory $reservationFactory
     * @param ContractorRepository $contractorRepository
     * @param ReservationRepository $reservationRepository
     * @param ReservationValidator $reservationValidator
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws Exception
     */
    public function addReservation(
        Request $request,
        string $contractorKey,
        ReservationFactory $reservationFactory,
        ContractorRepository $contractorRepository,
        ReservationRepository $reservationRepository,
        ReservationValidator $reservationValidator
    ): JsonResponse {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $visitDate = new \DateTime($request->get('visitDate'));
        $contractor = $contractorRepository->findOneByKey($contractorKey);

        $errors = $reservationValidator->validateInput($request);
        if (count($errors) > 0 && $contractor === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $reservations = $reservationRepository->findConflictingReservations($contractor, $visitDate);

        if (count($reservations) > 0) {
            return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
        }
        $reservation = $reservationFactory->createReservation(
            $email,
            $firstname,
            $lastname,
            $visitDate
        );
        $reservation->setContractor($contractor);
        $reservationRepository->save($reservation);

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
     * @Route("/api/profile/{contractorUsername}", methods="GET")
     * @param string $contractorUsername
     * @param ContractorRepository $contractorRepository
     * @param ContractorService $contractorService
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getContractorDetails(
        string $contractorUsername,
        ContractorRepository $contractorRepository,
        ContractorService $contractorService
    ): JsonResponse {
        $contractor = $contractorRepository->findOneBy(['username' => $contractorUsername]);

        if ($contractor) {
            $response = $contractorService->generateContractorCalendarResponse($contractor);
            if ($response) {
                return new JsonResponse($response);
            }
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
