<?php

namespace Detail\Blitline\Job\Definition;

interface AwsS3SourceInterface extends SourceInterface
{
    const TYPE_S3 = 's3';

    /**
     * @return string
     */
    public function getBucket();

    /**
     * @return string
     */
    public function getKey();
}
