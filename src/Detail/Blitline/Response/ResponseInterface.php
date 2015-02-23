<?php

namespace Detail\Blitline\Response;

interface ResponseInterface
{
    /**
     * @param array $responseData
     * @return BaseResponse
     */
    public static function fromRawResponse(array $responseData);

    /**
     * @param ResponseInterface $response
     * @return array
     */
    public static function toRawResponse(ResponseInterface $response);

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null);

    /**
     * @return string|null
     */
    public function getError();

    /**
     * @return boolean
     */
    public function hasError();

    /**
     * @return string
     */
    public function getJobId();
}
