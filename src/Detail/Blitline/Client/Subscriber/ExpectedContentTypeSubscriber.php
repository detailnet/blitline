<?php

namespace Detail\Blitline\Client\Subscriber;

use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExpectedContentTypeSubscriber implements
    EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array('command.after_send' => 'addExpectedContentType');
    }

    public function addExpectedContentType(Event $event)
    {
        /** @var \Guzzle\Service\Command\OperationCommand $command */
        $command = $event['command'];

        // Force response body to be interpreted as JSON even when the response's Content-Type
        // header is not "application/json".
        // The reason for this is that a request to /listen/{job_id} always seems to respond
        // with "text/plain" even though the body contains "application/json".
        if ($command->getRequest()->getHeader('Accept')->hasValue('application/json')) {
            $command->set('command.expects', 'application/json');
//            $command['command.expects'] = 'application/json';
        }
    }
}
