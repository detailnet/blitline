<?php

namespace DetailTest\Blitline\Client;

use PHPUnit_Framework_TestCase as TestCase;

//use Guzzle\Service\Description\ServiceDescription;

use Detail\Blitline\Client\BlitlineClient;
use Detail\Blitline\Client\Subscriber\ExpectedContentTypeSubscriber;
use Detail\Blitline\Job\JobBuilder;

class BlitlineClientTest extends TestCase
{
//    /** @var BlitlineClient */
//    protected $client;

    public function provideConfigValues()
    {
        return array(
            array('random_application_id'),
        );
    }

//    protected function setUp()
//    {
//        $this->client = new BlitlineClient();
//    }

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
        $this->assertEquals($config['application_id'], $client->getDefaultOption('query')['application_id']);
        $this->assertEquals('application/json', $client->getDefaultOption('headers')['Accept']);
        $this->assertEquals('https://api.blitline.com/', $client->getConfig('base_url'));
        $this->assertEquals($jobBuilder, $client->getJobBuilder());

        $hasExpectedContentTypeListener = false;

        foreach ($client->getEventDispatcher()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listenerConfig) {
                list($listener, $callback) = $listenerConfig;

                if ($listener instanceof ExpectedContentTypeSubscriber) {
                    $hasExpectedContentTypeListener = true;
                }
            }
        }

        $this->assertTrue(
            $hasExpectedContentTypeListener,
            'BlitlineClient is missing the listener of type "Detail\Blitline\Client\Subscriber\ExpectedContentTypeSubscriber"'
        );
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
            'application_id' => $applicationId
        );

        $client = BlitlineClient::factory($config);

        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('pollJob'));
        $this->assertEquals(
            'Detail\Blitline\Response\JobProcessed',
            $client->getCommand('pollJob')->getOperation()->getResponseClass()
        );

        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('submitJob'));
        $this->assertEquals(
            'Detail\Blitline\Response\JobSubmitted',
            $client->getCommand('submitJob')->getOperation()->getResponseClass()
        );
    }

    public function testJobBuilderCanBeSet()
    {
        $client = new BlitlineClient();

        $this->assertInstanceOf('Detail\Blitline\Job\JobBuilder', $client->getJobBuilder());

        $jobBuilder = new JobBuilder();

        $this->assertEquals($client, $client->setJobBuilder($jobBuilder));
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    public function testCommandsAcceptDefinitions()
    {
        $commandResponse = array('a' => 'b');

        $command = $this->getMock('Guzzle\Service\Command\OperationCommand');
        $command
            ->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($commandResponse));

        $client = $this->getMock('Detail\Blitline\Client\BlitlineClient', array('getCommand'));
        $client
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));

        /** @var BlitlineClient $client */

        $commandArgs = array('c' => 'd');

        $definition = $this->getMock('Detail\Blitline\Job\Definition\JobDefinition');
        $definition
            ->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue($commandArgs));

        $this->assertEquals($commandResponse, $client->__call('testCommand', array($definition)));
    }
}
