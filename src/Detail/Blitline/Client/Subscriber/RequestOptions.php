<?php

namespace Detail\Blitline\Client\Subscriber;

use GuzzleHttp\Command\Event\PreparedEvent;
use GuzzleHttp\Command\Guzzle\DescriptionInterface as ServiceDescriptionInterface;
use GuzzleHttp\Event\SubscriberInterface;

class RequestOptions implements
    SubscriberInterface
{
    /**
     * @var ServiceDescriptionInterface
     */
    protected $description;

    /**
     * @param ServiceDescriptionInterface $description
     */
    public function __construct(ServiceDescriptionInterface $description)
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return array('prepared' => array('onPrepared'));
    }

    /**
     * @param PreparedEvent $event
     */
    public function onPrepared(PreparedEvent $event)
    {
        $this->applyRequestOptions($event);
    }

    /**
     * @param PreparedEvent $event
     */
    protected function applyRequestOptions(PreparedEvent $event)
    {
        $command = $event->getCommand();
        $operation = $this->description->getOperation($command->getName());
        $requestOptions = $operation->getData('requestOptions');

        if (is_array($requestOptions)) {
            // Supports the following options:
            // 'connect_timeout', 'timeout', 'verify', 'ssl_key',
            // 'cert', 'proxy', 'debug', 'save_to', 'stream',
            // 'expect', 'future'
            $event->getRequest()->getConfig()->overwriteWith($requestOptions);
        }
    }
}
