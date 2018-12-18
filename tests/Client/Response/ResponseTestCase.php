<?php

namespace DetailTest\Blitline\Client\Response;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Psr7\Response as PsrResponse;
//use function GuzzleHttp\Psr7\stream_for;

use Detail\Blitline\Client\Response\Response;

abstract class ResponseTestCase extends TestCase
{
    /**
     * @param string $class
     * @param array $data
     * @param bool $abstract
     * @return Response
     */
    protected function getResponse(string $class, array $data = [], bool $abstract = false): Response
    {
        $httpResponse = $this->getHttpResponseForResult($data);

        if ($abstract) {
            $response = $this->getMockBuilder($class)
//                ->enableOriginalConstructor()
                ->setConstructorArgs([$httpResponse])
                ->getMockForAbstractClass();

            /** @var Response $response */
        } else {
            $response = new $class($httpResponse);
        }

        return $response;
    }

    /**
     * @param array $data
     * @return PsrResponse
     */
    protected function getHttpResponse(array $data = [])
    {
        return new PsrResponse(200, [], json_encode($data));
    }

    /**
     * @param array $data
     * @return PsrResponse
     */
    protected function getHttpResponseForResult(array $data = [])
    {
        return $this->getHttpResponse(['results' => $data]);
    }
}
