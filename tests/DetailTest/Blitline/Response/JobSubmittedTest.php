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

    public function testResponseCanBeCreatedFromData()
    {
        $key = 'key';
        $value = 'value';

        $response = JobSubmitted::fromData(array('results' => array($key => $value)));
        $this->assertInstanceOf(JobSubmitted::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testImagesCanBeGet()
    {
        $images = array(array('image_identifier' => 'some-image-identifier'));
        $result = array('images' => $images);

        $response = $this->getJobSubmittedResponse($result);

        $this->assertEquals($images, $response->getImages());
    }

    /**
     * @param array $data
     * @return JobSubmitted
     */
    protected function getJobSubmittedResponse(array $data)
    {
        return $this->getResponse(JobSubmitted::CLASS, $data);
    }
}
