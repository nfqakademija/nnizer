<?php


namespace App\Service;

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
}