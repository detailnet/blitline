<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Exception;
use Detail\Blitline\Response\JobProcessed;

class JobProcessedTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = JobProcessed::fromHttpResponse($this->getHttpResponseForResult());
        $this->assertInstanceOf(JobProcessed::CLASS, $response);

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $response = JobProcessed::fromHttpResponse($this->getHttpResponse());
        $response->getResult();
    }

    public function testResponseCanBeCreatedFromData()
    {
        $key = 'key';
        $value = 'value';

        $response = JobProcessed::fromData(['results' => [$key => $value]]);
        $this->assertInstanceOf(JobProcessed::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testImagesCanBeGet()
    {
        $images = [['image_identifier' => 'some-image-identifier']];
        $result = ['images' => $images];

        $response = $this->getJobProcessedResponse($result);

        $this->assertEquals($images, $response->getImages());
    }

    public function testFailedImageIdentifiersCanBeGet()
    {
        $response = $this->getJobProcessedResponse(['failed_image_identifiers' => []]);
        $this->assertFalse($response->hasFailedImageIdentifiers());

        $imageIdentifiers = ['200', '400'];

        $response = $this->getJobProcessedResponse(
            ['failed_image_identifiers' => $imageIdentifiers]
        );

        $this->assertTrue($response->hasFailedImageIdentifiers());
        $this->assertEquals($imageIdentifiers, $response->getFailedImageIdentifiers());

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $response = $this->getJobProcessedResponse(['failed_image_identifiers' => 'invalid']);
        $response->getFailedImageIdentifiers();
    }

    public function testOriginalMetaCanBeGet()
    {
        $meta = [['key' => 'value']];
        $result = ['original_meta' => $meta];

        $response = $this->getJobProcessedResponse($result);

        $this->assertEquals($meta, $response->getOriginalMeta());
    }

    /**
     * @param array $data
     * @return JobProcessed
     */
    protected function getJobProcessedResponse(array $data)
    {
        return $this->getResponse(JobProcessed::CLASS, $data);
    }
}
