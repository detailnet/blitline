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
    protected function getResponse($class, array $data = array())
    {
        $httpResponse = $this->getHttpResponseForResult($data);

        return $this->getMockForAbstractClass($class, array($httpResponse));
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function getHttpResponse(array $data = array())
    {
        $response = $this->getMock(Response::CLASS, array(), array(), '', false);
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
    protected function getHttpResponseForResult(array $data = array())
    {
        return $this->getHttpResponse(array('results' => $data));
    }
}
