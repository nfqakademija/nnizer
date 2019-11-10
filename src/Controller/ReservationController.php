<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ClientRegistrationFormType;
use App\Repository\ReservationRepository;
use App\Service\MailerService;
use Exception;
use mysql_xdevapi\DatabaseObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReservationController extends AbstractController
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
     * @param MailerService $mailer
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
        $reservation = new Reservation();
        $form = $this->createForm(ClientRegistrationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $reservation->setVerificationKey($reservation->generateActivationKey());
            $now = new \DateTime('now');
            $reservation->setVerificationKeyExpirationDate($now->modify('+15 minutes'));
            $reservation->setIsVerified(false);
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->sendSuccessfulRegistrationEmail(
                $reservation->getFirstname(),
                $reservation->getEmail(),
                $reservation->getVerificationKey()
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('client/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param string $firstname
     * @param string $email
     * @param string $key
     */
    private function sendSuccessfulRegistrationEmail(string $firstname, string $email, string $key): void
    {
        $this->mailer->sendMail(
            $this->renderView(
                'emails/client-register.html.twig',
                [
                    'user' => $firstname,
                    'key' => $key,
                ]
            ),
            $this->translator->trans('mailer.title.registration'),
            $email
        );
    }

    /**
     * @Route("/activate/{slug}", name="client_activate", methods="GET")
     * @param Request $request
     * @param string $slug
     * @return Response
     * @throws Exception
     */
    public function activate(Request $request, string $slug): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'verificationKey' => $slug,
        ]);

        if ($reservation != null && !$reservation->getIsVerified()) {
            $now = new \DateTime('now');
            if ($now < $reservation->getVerificationKeyExpirationDate()) {
                $reservation->setIsVerified(true);
                $this->sendSuccessfulVerificationEmail(
                    $reservation->getFirstName(),
                    $reservation->getEmail(),
                    $reservation->getVerificationKey()
                );
                $this->addFlash(
                    'notice',
                    $this->translator->trans('reservation.verified')
                );
            } else {
                $this->addFlash(
                    'notice',
                    $this->translator->trans('reservation.expired')
                );
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @param string $firstname
     * @param string $email
     * @param string $key
     */
    private function sendSuccessfulVerificationEmail(string $firstname, string $email, string $key): void
    {
        $this->mailer->sendMail(
            $this->renderView(
                'emails/client-verified.html.twig',
                [
                    'user' => $firstname,
                    'key' => $key,
                ]
            ),
            $this->translator->trans('mailer.title.registration'),
            $email
        );
    }

    /**
     * @Route("/cancel/{slug}", name="client_cancel", methods="GET")
     * @param Request $request
     * @param string $slug
     * @return Response
     * @throws Exception
     */
    public function cancel(Request $request, string $slug): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'verificationKey' => $slug,
        ]);

        if ($reservation != null && !$reservation->getIsCancelled()) {
            $reservation->setIsCancelled(true);
            $this->sendSuccessfulCancellationEmail($reservation->getFirstName(), $reservation->getEmail());
            $this->addFlash(
                'notice',
                $this->translator->trans('reservation.cancelled')
            );
        }

        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @param string $firstname
     * @param string $email
     */
    private function sendSuccessfulCancellationEmail(string $firstname, string $email): void
    {
        $this->mailer->sendMail(
            $this->renderView(
                'emails/client-cancel.html.twig',
                ['user' => $firstname]
            ),
            $this->translator->trans('mailer.title.registration'),
            $email
        );
    }
}
