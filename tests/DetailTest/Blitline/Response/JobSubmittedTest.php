<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Exception;
use Detail\Blitline\Response\JobSubmitted;

class JobSubmittedTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = JobSubmitted::fromHttpResponse($this->getHttpResponseForResult());
        $this->assertInstanceOf(JobSubmitted::CLASS, $response);

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $response = JobSubmitted::fromHttpResponse($this->getHttpResponse());
        $response->getResult();
    }
}
