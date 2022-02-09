<?php
namespace Tests\Cli\Ticket;

use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;



class AssignTicketCliCommandTest extends KernelTestCase
{
    private Application $app;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->app = new Application($kernel);
        $this->app->setAutoExit(false);

        // $this->app->run(new ArrayInput([
        //     'command' => 'app:reset', ['-q']
        // ]), new NullOutput());
    }

    public function test_it_should_fail_for_non_existing_ticket()
    {

        $command = $this->app->find('app:assign-ticket');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'ticket_id' => 'some_ticket_id',
                'user_id'   => 'some_user_id',
            ],
            ['capture_stderr_separately' => true],
        );

        $this->assertEquals($commandTester->getStatusCode(), 1);

        // Shouldn't this be in STDERR? $commandTester->getErrorOutput()
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            "[ERROR] No ticket found having the id: 'some_ticket_id'",
            $output
        );
    }

    public function test_it_should_fail_for_non_existing_user()
    {
        $command = $this->app->find('app:prepare-ticket');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'type'        => 'Some ticket type',
                'description' => 'Some description',
            ],
            ['capture_stderr_separately' => true],
        );
        $output = $commandTester->getDisplay();

        if (! preg_match(
            '/Uuid: ([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{8})/',
            $output,
            $matches,
        )) {
            throw new Exception("Unable to extract ticket uuid");
        }

        $ticketUuid = $matches[1];

        $command = $this->app->find('app:assign-ticket');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'ticket_id' => $ticketUuid,
                'user_id'   => 'some_user_id',
            ],
            ['capture_stderr_separately' => true],
        );

        $this->assertEquals($commandTester->getStatusCode(), 1);

        // Shouldn't this be in STDERR? $commandTester->getErrorOutput()
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            "[ERROR] No user found having the id: 'some_user_id'",
            $output
        );
    }
}