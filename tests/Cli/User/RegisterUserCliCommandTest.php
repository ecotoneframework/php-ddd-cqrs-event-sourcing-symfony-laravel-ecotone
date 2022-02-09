<?php
namespace Tests\Cli\User;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RegisterUserCliCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $command = $application->find('app:register-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'name' => 'me',
        ]);

        // $commandTester->assertCommandIsSuccessful();
        $this->assertEquals($commandTester->getStatusCode(), 0);

        $output = $commandTester->getDisplay();
        $this->assertStringStartsWith(
            "User registered: \n",
            $output
        );
        $this->assertMatchesRegularExpression(
            '/Uuid: [a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{8}/',
            $output
        );
    }
}