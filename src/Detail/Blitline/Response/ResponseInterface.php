<?php

namespace Detail\Blitline\Response;

interface ResponseInterface
{
    /**
     * @param array $responseData
     * @return BaseResponse
     */
    public static function fromResponse(array $responseData);

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null);
}
