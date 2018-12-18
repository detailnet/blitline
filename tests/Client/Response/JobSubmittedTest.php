<?php

namespace DetailTest\Blitline\Client\Response;

use Detail\Blitline\Client\Response\JobSubmitted;

class JobSubmittedTest extends ResponseTestCase
{
    public function testImagesCanBeGet()
    {
        $images = [['image_identifier' => 'some-image-identifier']];
        $result = ['images' => $images];

        $response = $this->getJobSubmittedResponse($result);

        $this->assertEquals($images, $response->getImages());
    }

    protected function getJobSubmittedResponse(array $data): JobSubmitted
    {
        /** @var JobSubmitted $response */
        $response = $this->getResponse(JobSubmitted::CLASS, $data);

        return $response;
    }
}
