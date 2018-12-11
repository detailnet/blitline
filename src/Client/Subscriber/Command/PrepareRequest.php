<?php

namespace Detail\Blitline\Client\Subscriber\Command;

use GuzzleHttp\Command\Event\PreparedEvent;
use GuzzleHttp\Command\Guzzle\DescriptionInterface as ServiceDescriptionInterface;
use GuzzleHttp\Event\SubscriberInterface;

class PrepareRequest implements
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
        return ['prepared' => ['onPrepared']];
    }

    /**
     * @param PreparedEvent $event
     */
    public function onPrepared(PreparedEvent $event)
    {
        // Supports the following options:
        // 'connect_timeout', 'timeout', 'verify', 'ssl_key',
        // 'cert', 'proxy', 'debug', 'save_to', 'stream',
        // 'expect', 'future'
        $event->getRequest()->getConfig()->overwriteWith(
            $this->getRequestOptions($event)
        );
    }

    /**
     * @param PreparedEvent $event
     * @return array
     */
    protected function getRequestOptions(PreparedEvent $event)
    {
        $command = $event->getCommand();
        $operation = $this->description->getOperation($command->getName());
        $requestOptions = $operation->getData('requestOptions');

        return is_array($requestOptions) ? $requestOptions : [];
    }
}
