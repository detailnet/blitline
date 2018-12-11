<?php

namespace Detail\Blitline\Response;

use GuzzleHttp\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface
{
    /**
     * @param HttpResponseInterface $response
     * @return ResponseInterface
     */
    public static function fromHttpResponse(HttpResponseInterface $response);

    /**
     * @param array $data
     * @return ResponseInterface
     */
    public static function fromData(array $data);

    /**
     * @return HttpResponseInterface
     */
    public function getHttpResponse();

    /**
     * @return array
     */
    public function getData();

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return mixed|null
     */
    public function getResult($expression, $failOnNull = false);
}
