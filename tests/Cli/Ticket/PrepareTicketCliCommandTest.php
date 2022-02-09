<?php
namespace Tests\Cli\Ticket;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PrepareTicketCliCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $command = $application->find('app:prepare-ticket');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'type'        => 'Some ticket type',
            'description' => 'Some description',
            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // $commandTester->assertCommandIsSuccessful();
        $this->assertEquals($commandTester->getStatusCode(), 0);

        $output = $commandTester->getDisplay();
        $this->assertMatchesRegularExpression(
            '/Uuid: [a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{8}/',
            $output
        );
    }
}