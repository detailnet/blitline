<?php

namespace Detail\Blitline\Response;

use GuzzleHttp\Exception\ParseException;
use GuzzleHttp\Message\Response as HttpResponse;
use GuzzleHttp\Message\ResponseInterface as HttpResponseInterface;
use GuzzleHttp\Stream\Stream;

use JmesPath\Env as JmesPath;

use Detail\Blitline\Exception;

abstract class BaseResponse implements
    ResponseInterface,
    \ArrayAccess
{
    const ROOT = 'results';

    /**
     * @var HttpResponseInterface
     */
    protected $httpResponse;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param HttpResponseInterface $response
     * @return static
     */
    public static function fromHttpResponse(HttpResponseInterface $response)
    {
        return new static($response);
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data)
    {
        $response = new HttpResponse(200, array(), Stream::factory(json_encode($data)));

        return static::fromHttpResponse($response);
    }

    /**
     * @param HttpResponseInterface $response
     */
    public function __construct(HttpResponseInterface $response)
    {
        $this->httpResponse = $response;
    }

    /**
     * @return HttpResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if ($this->data === null) {
            try {
                $this->data = $this->getHttpResponse()->json() ?: array();
            } catch (ParseException $e) {
                // Handle as server exception because it was the server that produces invalid JSON...
                throw new Exception\ServerException($e->getMessage(), 0, $e);
            }
        }

        return $this->data;
    }

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return array|mixed|null
     */
    public function getResult($expression = null, $failOnNull = false)
    {
        $result = $this->getResults();

        if ($expression !== null) {
            $result = $this->search($expression, $failOnNull);
        }

        return $result;
    }

    /**
     * @param string|integer $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        $data = $this->getResults();

        if (isset($data[$offset])) {
            return $data[$offset];
        }

        return null;
    }

    /**
     * @param string|integer $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception\DomainException('Data cannot be set');
    }

    /**
     * @param string|integer $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->getResults()[$offset]);
    }

    /**
     * @param string|integer $offset
     */
    public function offsetUnset($offset)
    {
        throw new Exception\DomainException('Data cannot be unset');
    }

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return mixed|null
     */
    protected function search($expression, $failOnNull = false)
    {
        $result = JmesPath::search($expression, $this->getResults());

        if ($result === null && $failOnNull === true) {
            throw new Exception\RuntimeException(sprintf('Result does not contain "%s"', $expression));
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getResults()
    {
        $data = $this->getData();

        if (!isset($data[self::ROOT]) || !is_array($data[self::ROOT])) {
            throw new Exception\RuntimeException(sprintf('Response does not contain "%s"', self::ROOT));
        }

        return $this->getData()[self::ROOT];
    }

    /**
     * @param $expression
     * @return array
     */
    protected function getArrayResult($expression)
    {
        try {
            $values = $this->getResult($expression, false);
        } catch (Exception\RuntimeException $e) {
            return array();
        }

        // Blitline seems to return null sometimes... (e.g. for original_meta)
        if ($values === null) {
            return array();
        }

        if (!is_array($values)) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Invalid value for "%s"; expected array but got %s',
                    $expression,
                    is_object($values) ? get_class($values) : gettype($values)
                )
            );
        }

        return $values;
    }
}
