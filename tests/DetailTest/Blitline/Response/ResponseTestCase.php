<?php

namespace DetailTest\Blitline\Response;

use PHPUnit_Framework_TestCase as TestCase;

abstract class ResponseTestCase extends TestCase
{
    /**
     * @param string $class
     * @param array $data
     * @return \Detail\Blitline\Response\ResponseInterface ResponseInterface
     */
    protected function getResponse($class, array $data)
    {
        return $this->getMockForAbstractClass($class, array($data));
    }

    /**
     * @param array $data
     * @return \Guzzle\Service\Command\OperationCommand
     */
    protected function getCommand(array $data)
    {
        $response = $this->getMock('Guzzle\Http\Message\Response', array(), array(), '', false);
        $response
            ->expects($this->any())
            ->method('json')
            ->will($this->returnValue($data));

        $command = $this->getMock('Guzzle\Service\Command\OperationCommand');
        $command
            ->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        return $command;
    }
}
