<?php


namespace App\Command;

use App\Entity\Reservation;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

class SendReviewFormsCommand extends Command
{
    protected static $defaultName = "app:send-review-links";

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var MailerService
     */
    private $mailer;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * SendReviewFormsCommand constructor.
     * @param EntityManagerInterface $em
     * @param MailerService $mailer
     * @param RouterInterface $router
     */
    public function __construct(EntityManagerInterface $em, MailerService $mailer, RouterInterface $router)
    {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
        $this->router = $router;
    }

    protected function configure()
    {
        $this->setDescription('Sends an email with review form to all reservations that should ' .
                                'have already been completed');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->router->getContext();
        $context->setHost('nnizer.projektai.nfqakademija.lt');

        $reservations = $this->em->getRepository(Reservation::class)
            ->findByInComplete(new \DateTime('now'));

        foreach ($reservations as $reservation) {
            $this->mailer->sendReviewEmail($reservation);
            $reservation->setIsCompleted(true);
            $this->em->persist($reservation);
        }
        $this->em->flush();
        $output->write("Done!");
    }
}
