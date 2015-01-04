<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Response\BaseResponse;

class BaseResponseTest extends ResponseTestCase
{
    public function testResultCanBeGet()
    {
        $resultKey = 'key';
        $resultValue = 'value';
        $result = array($resultKey => $resultValue);

        $response = $this->getResponse($result);

        $this->assertEquals($result, $response->getResult());
        $this->assertArrayHasKey($resultKey, $response->getResult());
        $this->assertEquals($resultValue, $response->getResult($resultKey));

        $this->setExpectedException('Detail\Blitline\Exception\RuntimeException');
        $response->getResult('non-existing-key');
    }

    public function testJobIdCanBeGet()
    {
        $jobId = 'some-job-id';
        $result = array('job_id' => $jobId);

        $response = $this->getResponse($result);

        $this->assertEquals($jobId, $response->getJobId());
    }

    public function testErrorsAreHandled()
    {
        $errorMessage = 'message';
        $result = array('error' => $errorMessage);

        $response = $this->getResponse($result);

        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isError());
        $this->assertEquals($errorMessage, $response->getError());
    }

    /**
     * @param array $data
     * @return BaseResponse
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\Blitline\Response\BaseResponse', $data);
    }
}
