<?php

namespace Detail\Blitline\Client\Response;

use GuzzleHttp\Command\Guzzle\Operation;
use GuzzleHttp\Psr7\Response as PsrResponse;

use Detail\Blitline\Client\Response\Resource as ClientResource;
use Detail\Blitline\Exception;

class ResourceResponse extends BaseResponse
{
    /**
     * @var ClientResource
     */
    protected $resource;

    /**
     * @param Operation $operation
     * @param PsrResponse $response
     * @return ResourceResponse
     */
    public static function fromOperation(Operation $operation, PsrResponse $response): Response
    {
        return new static($response);
    }

    public function getResource(): ClientResource
    {
        if ($this->resource === null) {
            $this->resource = new ClientResource($this->getData());
        }

        return $this->resource;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    protected function search(string $key)
    {
        return $this->getResource()->search(sprintf('*.%s | [0]', $key));
    }

    protected function searchArray(string $key): array
    {
        $values = $this->search($key);

        // Blitline seems to return null sometimes... (e.g. for original_meta)
        if ($values === null) {
            return [];
        }

        if (!is_array($values)) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Invalid value for "%s"; expected array but got %s',
                    $key,
                    is_object($values) ? get_class($values) : gettype($values)
                )
            );
        }

        return $values;
    }
}
