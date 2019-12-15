<?php

namespace App\EventListener;

use App\Service\MailerService;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @return string|Response
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
