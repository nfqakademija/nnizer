<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\AdminFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NewAdminCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:add-admin';
    /**
     * @var AdminFactory
     */
    private $adminFactory;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * PromoteUserCommand constructor.
     * @param UserRepository $userRepository
     * @param AdminFactory $adminFactory
     */
    public function __construct(
        UserRepository $userRepository,
        AdminFactory $adminFactory
    ) {
        $this->adminFactory = $adminFactory;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    /**
     * Command details
     */
    protected function configure()
    {
        $this
            ->setDescription('Create a new admin account')
            ->addArgument('email', InputArgument::REQUIRED, 'New admin account email')
            ->addArgument('password', InputArgument::REQUIRED, 'New admin account password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$this->userRepository->findByEmail($email)) {
            $user = $this->adminFactory->createAdmin($email, $password, 'System');
            $this->userRepository->save($user);
            $io->success('Admin role successfully added');
        } else {
            $io->error('Email address is already taken. ' .
                        'If you wish to promote specified email address to admin, use app:promote-user');
        }
    }
}
