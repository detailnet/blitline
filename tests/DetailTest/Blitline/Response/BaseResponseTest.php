<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Response\BaseResponse;

class BaseResponseTest extends ResponseTestCase
{
    public function provideErrors()
    {
        return array(
            array(
                array('error' => 'error #1'),
                'error #1',
            ),
            array(
                array('errors' => array('error #2')),
                'error #2',
            ),
            array(
                array('errors' => array('error #3', 'error #4')),
                'error #3',
            ),
            array(
                array('errors' => array('error_5_key' => 'error #5', 'error_6_key' => 'error #6')),
                'error #5',
            ),
        );
    }

    public function testResultCanBeGet()
    {
        $resultKey = 'key';
        $resultValue = 'value';
        $result = array($resultKey => $resultValue);

        $response = $this->getBaseResponse($result);

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

        $response = $this->getBaseResponse($result);

        $this->assertEquals($jobId, $response->getJobId());
    }

    /**
     * @param array $result
     * @param string $errorMessage
     * @dataProvider provideErrors
     */
    public function testErrorsAreHandled(array $result, $errorMessage)
    {
//        $errorMessage = 'message';
//        $result = array('error' => $errorMessage);

        $response = $this->getBaseResponse($result);

        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isError());
        $this->assertEquals($errorMessage, $response->getError());
    }

    /**
     * @param array $data
     * @return BaseResponse
     */
    protected function getBaseResponse(array $data)
    {
        return $this->getResponse('Detail\Blitline\Response\BaseResponse', $data);
    }
}
