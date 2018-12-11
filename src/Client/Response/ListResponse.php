<?php

namespace Detail\Blitline\Client\Response;

use ArrayIterator;

use GuzzleHttp\Command\Guzzle\Operation;
use GuzzleHttp\Psr7\Response as PsrResponse;

use Detail\Blitline\Client\Response\Resource as ClientResource;
use Detail\Blitline\Exception;

class ListResponse extends BaseResponse implements
    \IteratorAggregate
{
    /**
     * @var ClientResource[]
     */
    protected $resources = [];

    /**
     * @var string
     */
    private $dataRoot;

    /**
     * @param Operation $operation
     * @param PsrResponse $response
     * @return ListResponse
     */
    public static function fromOperation(Operation $operation, PsrResponse $response): Response
    {
        $operationConfig = $operation->toArray();

        if (!isset($operationConfig['responseDataRoot'])) {
            throw new Exception\RuntimeException(
                sprintf(
                    'No response data root configured for operation "%s"',
                    $operation->getName()
                )
            );
        }

        return new static($response, $operationConfig['responseDataRoot']);
    }

    public function __construct(PsrResponse $response, string $dataRoot)
    {
        parent::__construct($response);

        $this->dataRoot = $dataRoot;

        foreach ($this->getRawResources() as $resourceData) {
            $this->resources[] = new Resource($resourceData);
        }
    }

    /**
     * @return ClientResource[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * Count resources on current page
     *
     * @return int
     */
    public function getResourceCount(): int
    {
        return count($this->getResources());
    }

//    /**
//     * Count resources on all pages
//     *
//     * @return integer|null
//     */
//    public function getTotalResourceCount(): ?int
//    {
//        return isset($this->getData()['total_items']) ? (integer) $this->getData()['total_items'] : null;
//    }
//
//    /**
//     * Count number of pages
//     *
//     * @return integer|null
//     */
//    public function getPageCount(): ?int
//    {
//        return isset($this->getData()['page_count']) ? (integer) $this->getData()['page_count'] : null;
//    }
//
//    /**
//     * Get page size
//     *
//     * @return integer|null
//     */
//    public function getPageSize(): ?int
//    {
//        return isset($this->getData()['page_size']) ? (integer) $this->getData()['page_size'] : null;
//    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getResources());
    }

    private function getRawResources(): array
    {
        $data = $this->getData();

        if (!isset($data[$this->dataRoot])) {
            throw new Exception\RuntimeException(
                sprintf('Data root "%s" does not exist', $this->dataRoot)
            );
        }

        if (!is_array($data[$this->dataRoot])) {
            throw new Exception\RuntimeException(
                sprintf('Data root "%s" is not an array', $this->dataRoot)
            );
        }

        $resources = $data[$this->dataRoot];

        // Make sure the data for all resources are arrays
        $resourceIndex = 0;

        foreach ($resources as $resourceData) {
            if (!is_array($resourceData)) {
                throw new Exception\RuntimeException(
                    sprintf('Resource #%d is not an array', $resourceIndex)
                );
            }

            $resourceIndex++;
        }

        return $resources;
    }
}
