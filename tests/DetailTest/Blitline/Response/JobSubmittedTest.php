<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Response\JobSubmitted;

class JobSubmittedTest extends ResponseTestCase
{
    public function testRawResponseHandling()
    {
        $rawResponse = array('results' => array('job_id' => 1));
        $response = JobSubmitted::fromRawResponse($rawResponse);

        $this->assertEquals($rawResponse, JobSubmitted::toRawResponse($response));
        $this->assertEquals($rawResponse, $response->toArray());

        $this->setExpectedException('Detail\Blitline\Client\Exception\ServerException');
        JobSubmitted::fromRawResponse(array());
    }

    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = JobSubmitted::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\Blitline\Response\JobSubmitted', $response);

        $this->setExpectedException('Detail\Blitline\Client\Exception\ServerException');
        JobSubmitted::fromCommand($this->getCommand(array()));
    }

    /**
     * @param array $data
     * @return JobSubmitted
     */
    protected function getJobSubmittedResponse(array $data)
    {
        return $this->getResponse('Detail\Blitline\Response\JobSubmitted', $data);
    }
}
