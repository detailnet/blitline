<?php

namespace DetailTest\Blitline;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Command\Command;

use Detail\Blitline\BlitlineClient;
use Detail\Blitline\Exception;
use Detail\Blitline\Job\Definition\JobDefinition;
use Detail\Blitline\Job\JobBuilder;

class BlitlineClientTest extends TestCase
{
    public function provideConfigValues(): array
    {
        return [
            ['random_application_id'],
        ];
    }

    /**
     * @param string $applicationId
     * @dataProvider provideConfigValues
     */
    public function testFactoryReturnsClient(string $applicationId): void
    {
        $config = [
            'application_id' => $applicationId
        ];

        $jobBuilder = new JobBuilder();

        $client = BlitlineClient::factory($config, $jobBuilder);

        $this->assertInstanceOf(BlitlineClient::CLASS, $client);
        $this->assertEquals($config['application_id'], $client->getBlitlineApplicationId());
        $this->assertEquals('https://api.blitline.com/', $client->getBlitlineUrl());
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    public function testFactoryThrowsExceptionOnMissingConfigurationOptions(): void
    {
        $this->expectException(Exception\RuntimeException::CLASS);

        $config = [];

        BlitlineClient::factory($config);
    }

    public function testFactoryThrowsExceptionOnBlankConfigurationOptions(): void
    {
        $this->expectException(Exception\RuntimeException::CLASS);

        $config = [
            'application_id' => '',
        ];

        BlitlineClient::factory($config);
    }

    /**
     * @param string $applicationId
     * @dataProvider provideConfigValues
     */
    public function testClientHasCommands(string $applicationId): void
    {
        $config = [
            'application_id' => $applicationId,
        ];

        $client = BlitlineClient::factory($config);

        $this->assertTrue(is_callable([$client, 'pollJob']));
        $this->assertTrue(is_callable([$client, 'submitJob']));

//        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('pollJob'));
//        $this->assertEquals(
//            'Detail\Blitline\Response\JobProcessed',
//            $client->getCommand('pollJob')->getOperation()->getResponseClass()
//        );
//
//        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('submitJob'));
//        $this->assertEquals(
//            'Detail\Blitline\Response\JobSubmitted',
//            $client->getCommand('submitJob')->getOperation()->getResponseClass()
//        );
    }

    public function testJobBuilderCanBeSet(): void
    {
        $config = [
            'application_id' => 'random_application_id',
        ];

        $client = BlitlineClient::factory($config);

        $this->assertInstanceOf(JobBuilder::CLASS, $client->getJobBuilder());

        $jobBuilder = new JobBuilder();

        $client->setJobBuilder($jobBuilder);

        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    public function testCommandsAcceptDefinitions(): void
    {
        $commandResponse = ['a' => 'b'];

        $command = $this->getMockBuilder(Command::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(BlitlineClient::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(['getCommand', 'execute'])
            ->getMock();
        $client
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));
        $client
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($commandResponse));

        /** @var BlitlineClient $client */

        $commandArgs = ['c' => 'd'];

        $definition = $this->getMockBuilder(JobDefinition::CLASS)->getMock();
        $definition
            ->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue($commandArgs));

        $this->assertEquals($commandResponse, $client->__call('dummyCommand', [$definition]));
    }
}
