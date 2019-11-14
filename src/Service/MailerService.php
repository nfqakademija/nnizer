<?php


namespace App\Service;

use App\Entity\Reservation;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailerService extends AbstractController
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * MailerService constructor.
     * @param Swift_Mailer $mailer
     * @param ParameterBagInterface $params
     */
    public function __construct(Swift_Mailer $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }

    /**
     * @param string $template
     * @param string $mailSubject
     * @param string $emailTo
     */
    public function sendMail(string $template, string $mailSubject, string $emailTo): void
    {
        $message = (new \Swift_Message($mailSubject))
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo($emailTo)
            ->setBody(
                $template,
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * @param Reservation $reservation
     * @param TranslatorInterface $translatorInterface
     */
    public function sendSuccessfulRegistrationEmail(
        Reservation $reservation,
        TranslatorInterface $translatorInterface
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-register.html.twig',
                [
                    'user' => $reservation->getFirstname(),
                    'key' => $reservation->getVerificationKey(),
                    'date' => $reservation->getVisitDate()->format('Y-m-d H-i'),
                    'provider' => $reservation->getContractor()
                ]
            ),
            $translatorInterface->trans('email.heading.registered'),
            $reservation->getEmail()
        );
    }
    /**
     * @param Reservation $reservation
     * @param TranslatorInterface $translatorInterface
     */
    public function sendSuccessfulVerificationEmail(
        Reservation $reservation,
        TranslatorInterface $translatorInterface
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-verified.html.twig',
                [
                    'user' => $reservation->getFirstname(),
                    'key' => $reservation->getVerificationKey(),
                ]
            ),
            $translatorInterface->trans('email.heading.verified'),
            $reservation->getEmail()
        );
    }
    /**
     * @param Reservation $reservation
     * @param TranslatorInterface $translatorInterface
     */
    public function sendSuccessfulCancellationEmail(
        Reservation $reservation,
        TranslatorInterface $translatorInterface
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-cancel.html.twig',
                ['user' => $reservation->getFirstname()]
            ),
            $translatorInterface->trans('email.heading.cancelled'),
            $reservation->getEmail()
        );
    }
}
