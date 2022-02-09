<?php

declare(strict_types=1);

namespace App\UI\Cli\Ticket;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Exception;
use Assert\AssertionFailedException;

use App\Domain\Ticket\Ticket;
use Ecotone\Modelling\CommandBus;

class PrepareTicketCliCommand extends Command
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
            ->setName('app:prepare-ticket')
            ->setDescription('Given a type and a description, generates a new ticket.')
            ->addArgument('type', InputArgument::REQUIRED, 'Ticket type')
            ->addArgument('description', InputArgument::REQUIRED, 'Ticket description')
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
            Ticket::PREPARE_TICKET_TICKET,
            [
                "ticketType" =>$input->getArgument('type'),
                "description" =>$input->getArgument('description'),
            ]
        );

        $output->writeln('<info>Ticket prepared: </info>');
        // $output->writeln('');
        $output->writeln("Uuid: {$result['ticketId']}");

        return Command::SUCCESS;
    }
}
