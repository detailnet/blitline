<?php

namespace DetailTest\Blitline\Client;

use PHPUnit_Framework_TestCase as TestCase;

use GuzzleHttp\Command\Command;
use GuzzleHttp\Command\Exception\CommandException;

use Detail\Blitline\Client\BlitlineClient;
use Detail\Blitline\Exception;
use Detail\Blitline\Job\Definition\JobDefinition;
use Detail\Blitline\Job\JobBuilder;

class BlitlineClientTest extends TestCase
{
    /**
     * @return array
     */
    public function provideConfigValues()
    {
        return array(
            array('random_application_id'),
        );
    }

    /**
     * @param $applicationId
     * @dataProvider provideConfigValues
     */
    public function testFactoryReturnsClient($applicationId)
    {
        $config = array(
            'application_id' => $applicationId
        );

        $jobBuilder = new JobBuilder();

        $client = BlitlineClient::factory($config, $jobBuilder);

        $this->assertInstanceOf('Detail\Blitline\Client\BlitlineClient', $client);
        $this->assertEquals($config['application_id'], $client->getBlitlineApplicationId());
        $this->assertEquals('https://api.blitline.com/', $client->getBlitlineUrl());
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    /**
     * @expectedException \Detail\Blitline\Exception\InvalidArgumentException
     */
    public function testFactoryThrowsExceptionOnMissingConfigurationOptions()
    {
        $config = array();

        BlitlineClient::factory($config);
    }

    /**
     * @expectedException \Detail\Blitline\Exception\InvalidArgumentException
     */
    public function testFactoryThrowsExceptionOnBlankConfigurationOptions()
    {
        $config = array(
            'application_id' => '',
        );

        BlitlineClient::factory($config);
    }

    /**
     * @param $applicationId
     * @dataProvider provideConfigValues
     */
    public function testClientHasCommands($applicationId)
    {
        $config = array(
            'application_id' => $applicationId,
        );

        $client = BlitlineClient::factory($config);

        $this->assertTrue(is_callable(array($client, 'pollJob')));
        $this->assertTrue(is_callable(array($client, 'submitJob')));

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

    public function testJobBuilderCanBeSet()
    {
        $config = array(
            'application_id' => 'random_application_id',
        );

        $client = BlitlineClient::factory($config);

        $this->assertInstanceOf(JobBuilder::CLASS, $client->getJobBuilder());

        $jobBuilder = new JobBuilder();

        $this->assertEquals($client, $client->setJobBuilder($jobBuilder));
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    public function testCommandExceptionsAreHandled()
    {
//        $commandResponse = array('a' => 'b');
//
        $command = $this->getMockBuilder(Command::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $exception = $this->getMockBuilder(CommandException::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var CommandException $exception */

        $client = $this->getMockBuilder(BlitlineClient::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(array('getCommand', 'execute'))
            ->getMock();
        $client
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));
        $client
            ->expects($this->any())
            ->method('execute')
            ->will($this->throwException($exception));

        /** @var BlitlineClient $client */

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $client->__call('dummyCommand', array());
    }

    public function testCommandsAcceptDefinitions()
    {
        $commandResponse = array('a' => 'b');

        $command = $this->getMockBuilder(Command::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(BlitlineClient::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(array('getCommand', 'execute'))
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

        $commandArgs = array('c' => 'd');

        $definition = $this->getMock(JobDefinition::CLASS);
        $definition
            ->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue($commandArgs));

        $this->assertEquals($commandResponse, $client->__call('dummyCommand', array($definition)));
    }
}
