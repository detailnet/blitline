<?php

namespace DetailTest\Blitline\Response;

use PHPUnit_Framework_TestCase as TestCase;

use GuzzleHttp\Message\Response;

use Detail\Blitline\Response\ResponseInterface;

abstract class ResponseTestCase extends TestCase
{
    /**
     * @param string $class
     * @param array $data
     * @return ResponseInterface
     */
    protected function getResponse($class, array $data = [])
    {
        $httpResponse = $this->getHttpResponseForResult($data);

        return $this->getMockForAbstractClass($class, [$httpResponse]);
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function getHttpResponse(array $data = [])
    {
        $response = $this->getMock(Response::CLASS, [], [], '', false);
        $response
            ->expects($this->any())
            ->method('json')
            ->will($this->returnValue($data));

        return $response;
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function getHttpResponseForResult(array $data = [])
    {
        return $this->getHttpResponse(['results' => $data]);
    }
}
