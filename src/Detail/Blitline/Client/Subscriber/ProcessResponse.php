<?php

namespace Detail\Blitline\Client\Subscriber;

use GuzzleHttp\Command\Event\ProcessEvent;
use GuzzleHttp\Command\Guzzle\DescriptionInterface as ServiceDescriptionInterface;
use GuzzleHttp\Event\SubscriberInterface;

use Detail\Blitline\Exception;
use Detail\Blitline\Response\ResponseInterface;

class ProcessResponse implements
    SubscriberInterface
{
    /**
     * @var ServiceDescriptionInterface
     */
    private $description;

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
        return array('process' => array('onProcess'));
    }

    /**
     * @param ProcessEvent $event
     */
    public function onProcess(ProcessEvent $event)
    {
        // Only add a result object if no exception was encountered.
        if ($event->getException()) {
            return;
        }

        $command = $event->getCommand();

        // Do not overwrite a previous result
        if ($event->getResult()) {
            return;
        }

        $operation = $this->description->getOperation($command->getName());

        $responseClass = $operation->getResponseModel();

        if ($responseClass === null) {
            throw new Exception\RuntimeException(
                sprintf('No response class configured for operation "%s"', $command->getName())
            );
        }

        if (!class_exists($responseClass)) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Response class "%s" of operation "%s" does not exist',
                    $responseClass,
                    $command->getName()
                )
            );
        }

        /** @todo We could check if the response class implements ResponseInterface */

        /** @var ResponseInterface $responseClass */

        $result = $responseClass::fromOperation($operation, $event);

        $event->setResult($result);
    }
}
