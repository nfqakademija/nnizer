<?php

namespace App\EventListener;

use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{

    /**
     * @var MailerService
     */
    private $mailer;

    /**
     * @var String
     */
    private $environment;

    /**
     * ExceptionListener constructor.
     * @param MailerService $mailer
     * @param String $environment
     */
    public function __construct(String $environment, MailerService $mailer)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        if ($this->environment === 'dev') {
            return;
        }

        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {
            $event->setException(new NotFoundHttpException('Page not found'));
        } else {
            $this->mailer->sendExceptionEmail($exception);
            $event->setException(new \Exception('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
}
