<?php

namespace Detail\Blitline\Client\Listener;

use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExpectedContentTypeListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array('command.after_send' => 'addExcpectedContentType');
    }

    public function addExcpectedContentType(Event $event)
    {
        /** @var \Guzzle\Service\Command\CommandInterface $command */
        $command = $event['command'];

        // Force response body to be interpreted as JSON even when the response's Content-Type
        // header is not "application/json".
        // The reason for this is that a request to /listen/{jobId} always seems to respond
        // with "text/plain" even though the body contains "application/json".
        if ($command->getRequest()->getHeader('Accept')->hasValue('application/json')) {
            $command['command.expects'] = 'application/json';
        }
    }
} 
