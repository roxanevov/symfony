<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommande extends Command
{
    protected static $defaultName = 'app:create-user';

    protected function configure()
    {
        $this
            ->setDescription('create user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of the user')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $output->writeln(sprintf('<info>Email : %s, password %s</info>', $email, $password));
    }

}