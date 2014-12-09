<?php

namespace DetailTest\Blitline\Client;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\Blitline\Client\BlitlineClient;
use Detail\Blitline\Client\Listener\ExpectedContentTypeListener;

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

        $client = BlitlineClient::factory($config);

        $this->assertInstanceOf('Detail\Blitline\Client\BlitlineClient', $client);
        $this->assertEquals($config['application_id'], $client->getDefaultOption('query')['application_id']);
        $this->assertEquals('application/json', $client->getDefaultOption('headers')['Accept']);
        $this->assertEquals('https://api.blitline.com/', $client->getConfig('base_url'));

        $hasExpectedContentTypeListener = false;

        foreach ($client->getEventDispatcher()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listenerConfig) {
                list($listener, $callback) = $listenerConfig;

                if ($listener instanceof ExpectedContentTypeListener) {
                    $hasExpectedContentTypeListener = true;
                }
            }
        }

        $this->assertTrue(
            $hasExpectedContentTypeListener,
            'BlitlineClient is missing the listener of type "Detail\Blitline\Client\Listener\ExpectedContentTypeListener"'
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
        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('postJob'));
    }
}
