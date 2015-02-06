<?php

namespace  Detail\Blitline\Response;

use Detail\Blitline\Client\Exception;
use Detail\Blitline\Exception\RuntimeException;

use Guzzle\Common\Exception\RuntimeException as GuzzleRuntimeException;
use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface as GuzzleResponseInterface;

abstract class BaseResponse implements
    ResponseInterface,
    GuzzleResponseInterface
{
    /**
     * @var array
     */
    protected $result = array();

    /**
     * @param OperationCommand $command
     * @return BaseResponse
     */
    public static function fromCommand(OperationCommand $command)
    {
        // Note that we should only get successful responses.
        // For 4xx and 5xx errors an exception was thrown by our error handler.
        // The only cases left to handle here are invalid JSON and an unexpected response format.

        $response = $command->getResponse();

        try {
            $responseData = $response->json();
        } catch (GuzzleRuntimeException $e) {
            throw new Exception\ServerException($e->getMessage(), 0, $e);
        }

        return static::fromResponse($responseData);
    }

    /**
     * @param array $responseData
     * @return BaseResponse
     */
    public static function fromResponse(array $responseData)
    {
        if (!isset($responseData['results']) || !is_array($responseData['results'])) {
            throw new Exception\ServerException('Unexpected response format; contains no result');
        }

        $result = $responseData['results'];
        $error = null;

        if (isset($result['errors']) && is_array($result['errors'])) {
            $error = current($result['errors']);
        } elseif (isset($result['error'])) {
            $error = $result['error'];
        }

        if ($error !== null) {
            // We don't know if it's a client or server exception... (most likely a client exception, though)
            throw new Exception\BadResponseException((string) $error);
        }

        return new static($result);
    }

    /**
     * @param array $result
     */
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null)
    {
        $result = $this->result;

        if ($key !== null) {
            if (!isset($result[$key])) {
                throw new RuntimeException(sprintf('Result does not contain "%s"', $key));
            }

            $result = $result[$key];
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->getResult('job_id');
    }
}
