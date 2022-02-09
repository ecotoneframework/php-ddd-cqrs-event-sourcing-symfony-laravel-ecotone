<?php

declare(strict_types=1);

namespace App\UI\Cli\Ticket;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use Exception;
use Assert\AssertionFailedException;

use App\Domain\Ticket\Ticket;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\AggregateNotFoundException;

class AssignTicketCliCommand extends Command
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
            ->setName('app:assign-ticket')
            ->setDescription('Assigns a ticket to a user.')
            ->addArgument('ticket_id', InputArgument::REQUIRED, 'Id of the ticket to assign')
            ->addArgument('user_id', InputArgument::REQUIRED, 'User id to assign the ticket to')
        ;
    }

    /**
     * @throws Exception
     * @throws Ecotone\Modelling\AggregateNotFoundException
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ticketId = $input->getArgument('ticket_id');
        $userId = $input->getArgument('user_id');
        $io = new SymfonyStyle($input, $output);

        try {
            $result = $this->commandBus->sendWithRouting(
                Ticket::ASSIGN_TICKET,
                [
                    "ticketId" => $ticketId,
                    "assignTo" => $userId,
                ]
            );
        } catch (AggregateNotFoundException $e) {
            if (! preg_match("/Aggregate ([^\s]+)/", $e->getMessage(), $matches)) {
                // AggregateNotFoundException message has changed
                throw $e;
            }

            if ($matches[1] == Ticket::class) {
                $io->error("No ticket found having the id: '$ticketId'");
            } elseif ($matches[1] == User::class) {
                $io->error("No user found having the id: '$userId'");
            } else {
                // Unhandled aggregate
                throw $e;
            }

            return Command::FAILURE;
        }

        $io->success("Ticket '$ticketId' assigned to user '$userId'");

        return Command::SUCCESS;
    }
}
