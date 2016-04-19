<?php

namespace Detail\Blitline\Client\Subscriber;

use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Exception\ParseException;
use GuzzleHttp\Message\ResponseInterface as Response;
use GuzzleHttp\Subscriber\HttpError as InternalErrorHandler;

class ErrorHandler extends InternalErrorHandler
{
    /**
     * @param CompleteEvent $event
     */
    public function onComplete(CompleteEvent $event)
    {
        $response = $event->getResponse();
        $response->setReasonPhrase($this->getErrorMessage($response));

        parent::onComplete($event);
    }

    /**
     * Extract more detailed error message.
     *
     * @param Response $response
     * @return string
     */
    protected function getErrorMessage(Response $response)
    {
        // This is the default:
        $error = $response->getReasonPhrase(); // e.g. "Bad Request"

        try {
            // We might be able to fetch an error message from the response
            $responseData = $response->json();

            if (isset($responseData['title'])) {
                $error = $responseData['title'];
            }

            if (isset($responseData['detail'])) {
                $error .= ': ' . $responseData['detail'];
            }
        } catch (ParseException $e) {
            // Do nothing
        }

        return (string) $error;
    }
}
