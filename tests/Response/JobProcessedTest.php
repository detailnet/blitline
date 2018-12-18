<?php

namespace DetailTest\Blitline\Response;

use Detail\Blitline\Exception;
use Detail\Blitline\Response\JobProcessed;

class JobProcessedTest extends ResponseTestCase
{
    public function testImagesCanBeGet(): void
    {
        $images = [['image_identifier' => 'some-image-identifier']];
        $result = ['images' => $images];

        $response = $this->getJobProcessedResponse($result);

        $this->assertEquals($images, $response->getImages());
    }

    public function testFailedImageIdentifiersCanBeGet(): void
    {
        $response = $this->getJobProcessedResponse(['failed_image_identifiers' => []]);
        $this->assertFalse($response->hasFailedImageIdentifiers());

        $imageIdentifiers = ['200', '400'];

        $response = $this->getJobProcessedResponse(
            ['failed_image_identifiers' => $imageIdentifiers]
        );

        $this->assertTrue($response->hasFailedImageIdentifiers());
        $this->assertEquals($imageIdentifiers, $response->getFailedImageIdentifiers());

        $this->expectException(Exception\RuntimeException::CLASS);
        $response = $this->getJobProcessedResponse(['failed_image_identifiers' => 'invalid']);
        $response->getFailedImageIdentifiers();
    }

    public function testOriginalMetaCanBeGet(): void
    {
        $meta = [['key' => 'value']];
        $result = ['original_meta' => $meta];

        $response = $this->getJobProcessedResponse($result);

        $this->assertEquals($meta, $response->getOriginalMeta());
    }

    protected function getJobProcessedResponse(array $data): JobProcessed
    {
        /** @var JobProcessed $response */
        $response = $this->getResponse(JobProcessed::CLASS, $data);

        return $response;
    }
}
