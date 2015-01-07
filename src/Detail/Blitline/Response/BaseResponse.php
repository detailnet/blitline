<?php

namespace  Detail\Blitline\Response;

use Detail\Blitline\Exception\RuntimeException;

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
     * @return ResponseInterface
     */
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse()->json();

        if (!isset($response['results']) || !is_array($response['results'])) {
            throw new RuntimeException('Unexpected response format; contains no result');
        }

        return new static($response['results']);
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
     * @return boolean
     */
    public function isSuccess()
    {
        return !$this->isError();
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return $this->getError() !== null;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        $result = $this->getResult();

        $error = null;

        if (isset($result['errors']) && is_array($result['errors'])) {
            $error = current($result['errors']);
        } else if (isset($result['error'])) {
            $error = $result['error'];
        }

        return ($error !== null) ? (string) $error : null;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->getResult('job_id');
    }
}
