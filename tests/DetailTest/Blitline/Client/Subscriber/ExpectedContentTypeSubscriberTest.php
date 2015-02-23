<?php

namespace DetailTest\Blitline\Client\Listener;

use PHPUnit_Framework_TestCase as TestCase;

use Guzzle\Common\Event;

use Detail\Blitline\Client\Subscriber\ExpectedContentTypeSubscriber;

class ExpectedContentTypeSubscriberTest extends TestCase
{
    /** @var ExpectedContentTypeSubscriber */
    protected $subscriber;

    protected function setUp()
    {
        $this->subscriber = new ExpectedContentTypeSubscriber();
    }

    public function testIsSubscribedToAfterSendEvent()
    {
        $events = $this->subscriber->getSubscribedEvents();

        $this->assertArrayHasKey('command.after_send', $events);
    }

    public function testCommandExpectsJsonWhenClientAcceptsJson()
    {
        $header = $this->getMock('Guzzle\Http\Message\Header\HeaderInterface');
        $header
            ->expects($this->once())
            ->method('hasValue')
            ->will($this->returnValue(true));

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->will($this->returnValue($header));

        $command = $this->getMock('Guzzle\Service\Command\OperationCommand', array('getRequest'));
        $command
            ->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        /** @var \Guzzle\Service\Command\OperationCommand $command */
        $event = new Event();
        $event['command'] = $command;

        $this->assertNull($command->get('command.expects'));

        $this->subscriber->addExpectedContentType($event);

        $this->assertEquals('application/json', $command->get('command.expects'));
    }
}