<?php

declare(strict_types=1);

namespace App\UI\Cli\User;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Exception;
use Assert\AssertionFailedException;

use App\Domain\User\User;
use Ecotone\Modelling\CommandBus;

class RegisterUserCliCommand extends Command
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:register-user')
            ->setDescription('Given a name, registers a new user.')
            ->addArgument('name', InputArgument::REQUIRED, 'User name')
            ;
    }

    /**
     * @throws Exception
     * @throws AssertionFailedException
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->commandBus->sendWithRouting(
            "registerUser",
            $input->getArgument('name'),
        );

        $output->writeln('<info>User registered: </info>');
        $output->writeln("Uuid: {$result['userId']}");

        return Command::SUCCESS;
    }
}
