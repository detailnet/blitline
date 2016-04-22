<?php

namespace Detail\Blitline\Exception;

use GuzzleHttp\Command\Exception as CommandException;
use GuzzleHttp\Exception as HttpException;

class OperationException extends RuntimeException
{
    /**
     * @param \Exception $exception
     * @return ClientException|ServerException
     */
    public static function wrapException(\Exception $exception)
    {
        $causingException = $exception->getPrevious();

        if ($causingException instanceof HttpException\ConnectException // Timeout, etc.
            || $exception instanceof CommandException\CommandServerException // 5xx errors
        ) {
            return new ServerException(
                sprintf('Server error: %s', $exception->getMessage()),
                $exception
            );
        }

        // Consider everything else as client side errors:
        // - Server side validation errors resulting in code 400 (CommandException\CommandClientException)
        // - Client side Validation, request is not sent (HttpException\CommandException)
        // - And every else...
        return new ClientException(
            sprintf('Client error: %s', $exception->getMessage()),
            $exception
        );
    }

    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
