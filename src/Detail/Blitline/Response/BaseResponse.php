<?php

namespace Detail\Blitline\Response;

use Detail\Blitline\Client\Exception as ClientException;
use Detail\Blitline\Exception;

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
            throw new ClientException\ServerException($e->getMessage(), 0, $e);
        }

        return static::fromRawResponse($responseData);
    }

    /**
     * @param array $responseData
     * @return BaseResponse
     */
    public static function fromRawResponse(array $responseData)
    {
        if (!isset($responseData['results']) || !is_array($responseData['results'])) {
            throw new ClientException\ServerException('Unexpected response format; contains no result');
        }

        return new static($responseData['results']);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    public static function toRawResponse(ResponseInterface $response)
    {
        return array('results' => $response->getResult());
    }

    /**
     * @param array $result
     */
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return self::toRawResponse($this);
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
                throw new Exception\ResponseException(sprintf('Result does not contain "%s"', $key));
            }

            $result = $result[$key];
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        try {
            $error = $this->getResult('errors');

            if (is_array($error)) {
                $error = current($error);
            }

            return $error;
        } catch (Exception\ResponseException $e) {
            try {
                return $this->getResult('error');
            } catch (Exception\ResponseException $e) {
                return null;
            }
        }
    }

    /**
     * @return boolean
     */
    public function hasError()
    {
        return $this->getError() !== null;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->getResult('job_id');
    }
}
