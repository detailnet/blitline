<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Response\JobResponse;

class JobResponseTest extends ResponseTestCase
{
    /**
     * @return array
     */
    public function provideErrors()
    {
        return [
            [
                ['error' => 'error #1'],
                ['error #1'],
            ],
            [
                ['errors' => ['error #2']],
                ['error #2'],
            ],
            [
                ['errors' => ['error #3', 'error #4']],
                ['error #3', 'error #4'],
            ],
            [
                ['errors' => ['error_5_key' => 'error #5', 'error_6_key' => 'error #6']],
                ['error #5', 'error #6'],
            ],
        ];
    }

    public function testJobIdCanBeGet()
    {
        $jobId = 'some-job-id';
        $result = ['job_id' => $jobId];

        $response = $this->getJobResponse($result);

        $this->assertEquals($jobId, $response->getJobId());
    }

    /**
     * @param array $result
     * @param array $errors
     * @dataProvider provideErrors
     */
    public function testErrorsAreHandled(array $result, array $errors)
    {
        $response = $this->getJobResponse($result);

        $this->assertTrue($response->hasErrors());
        $this->assertEquals($errors, $response->getErrors());
    }

    /**
     * @param array $data
     * @return JobResponse
     */
    protected function getJobResponse(array $data)
    {
        return $this->getResponse(JobResponse::CLASS, $data);
    }
}
