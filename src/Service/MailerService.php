<?php


namespace App\Service;

use App\Entity\Contractor;
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MailerService constructor.
     * @param Swift_Mailer $mailer
     * @param ParameterBagInterface $params
     * @param TranslatorInterface $translator
     */
    public function __construct(Swift_Mailer $mailer, ParameterBagInterface $params, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->params = $params;
        $this->translator = $translator;
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
     */
    public function sendSuccessfulRegistrationEmail(
        Reservation $reservation
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-register.html.twig',
                [
                    'user' => $reservation->getFirstname(),
                    'key' => $reservation->getVerificationKey(),
                    'date' => $reservation->getVisitDate()->format('Y-m-d H:i'),
                    'provider' => $reservation->getContractor()->getUsername()
                ]
            ),
            $this->translator->trans('email.heading.registered'),
            $reservation->getEmail()
        );
    }

    /**
     * @param Reservation $reservation
     */
    public function sendSuccessfulVerificationEmail(
        Reservation $reservation
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-verified.html.twig',
                [
                    'user' => $reservation->getFirstname(),
                    'key' => $reservation->getVerificationKey(),
                ]
            ),
            $this->translator->trans('email.heading.verified'),
            $reservation->getEmail()
        );
    }

    /**
     * @param Reservation $reservation
     */
    public function sendSuccessfulCancellationEmail(
        Reservation $reservation
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-cancel.html.twig',
                ['user' => $reservation->getFirstname()]
            ),
            $this->translator->trans('email.heading.cancelled'),
            $reservation->getEmail()
        );
    }
    /**
     * @param Reservation $reservation
     */
    public function sendReviewEmail(
        Reservation $reservation
    ): void {
        $this->sendMail(
            $this->renderView(
                'emails/client-review.html.twig',
                ['hash' => $reservation->getVerificationKey(), 'user' => $reservation->getFirstname()]
            ),
            $this->translator->trans('email.heading.review'),
            $reservation->getEmail()
        );
    }

    /**
     * @param Contractor $contractor
     */
    public function sendLostPasswordEmail(Contractor $contractor)
    {
        $this->sendMail(
            $this->renderView(
                'emails/lost-password.html.twig',
                ['hash' => $contractor->getLostPassword()->getResetKey(), 'user' => $contractor->getUsername()]
            ),
            $this->translator->trans('email.heading.lostpassword'),
            $contractor->getEmail()
        );
    }

    /**
     * @param \Exception $exception
     */
    public function sendExceptionEmail(
        \Exception $exception
    ): void {
        $exception->getTraceAsString();
        $details = 'Exception has been thrown in ' . $exception->getFile() .
            ' on line ' . $exception->getLine() . '<br>Exception message: ' . $exception->getMessage();
        $this->sendMail(
            $details,
            'Exception!',
            $this->getParameter('admin_email')
        );
    }
}
