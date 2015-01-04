<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Response\JobSubmitted;

class JobSubmittedTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = JobSubmitted::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\Blitline\Response\JobSubmitted', $response);

        $this->setExpectedException('Detail\Blitline\Exception\RuntimeException');
        JobSubmitted::fromCommand($this->getCommand(array()));
    }

    /**
     * @param array $data
     * @return JobSubmitted
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\Blitline\Response\JobSubmitted', $data);
    }
}
