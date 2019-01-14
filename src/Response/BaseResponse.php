<?php

namespace Detail\Blitline\Response;

use GuzzleHttp\Psr7\Response as PsrResponse;

use Detail\Blitline\Exception;

abstract class BaseResponse implements
    Response
{
    /**
     * @var PsrResponse
     */
    protected $response;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(PsrResponse $response)
    {
        $this->response = $response;
        $this->data = $this->extractData();
    }

    public function getHttpResponse(): PsrResponse
    {
        return $this->response;
    }

    public function toArray(): array
    {
        return $this->getData();
    }

    protected function getData(): array
    {
        return $this->data;
    }

    protected function extractData(): array
    {
        try {
            $data = $this->decodeJson($this->getHttpResponse()->getBody());

            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                sprintf('Failed to extract data from HTTP response: %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    private function decodeJson(?string $value): array
    {
        $data = json_decode($value, true);

        if (!$data) {
            $message = 'Unknown error';
            $jsonError = json_last_error();

            if ($jsonError !== JSON_ERROR_NONE) {
                $jsonMessage = json_last_error_msg();

                if ($jsonMessage !== false) {
                    $message = $jsonMessage;
                }
            }

            throw new Exception\RuntimeException(
                sprintf('Unable to decode JSON: %s', $message)
            );
        } elseif (!is_array($data)) {
            throw new Exception\RuntimeException(
                sprintf('Invalid JSON: Expected array but got %s', gettype($data))
            );
        }

        return $data;
    }
}
