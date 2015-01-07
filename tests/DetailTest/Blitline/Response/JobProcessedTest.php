<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Response\JobProcessed;

class JobProcessedTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = JobProcessed::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\Blitline\Response\JobProcessed', $response);

        $this->setExpectedException('Detail\Blitline\Exception\RuntimeException');
        JobProcessed::fromCommand($this->getCommand(array()));
    }

    public function testImagesCanBeGet()
    {
        $images = array(array('image_identifier' => 'some-image-identifier'));
        $result = array('images' => $images);

        $response = $this->getJobProcessedResponse($result);

        $this->assertEquals($images, $response->getImages());
    }

    public function testOriginalMetaCanBeGet()
    {
        $meta = array(array('key' => 'value'));
        $result = array('original_meta' => $meta);

        $response = $this->getJobProcessedResponse($result);

        $this->assertEquals($meta, $response->getOriginalMeta());
    }

    /**
     * @param array $data
     * @return JobProcessed
     */
    protected function getJobProcessedResponse(array $data)
    {
        return $this->getResponse('Detail\Blitline\Response\JobProcessed', $data);
    }
}
