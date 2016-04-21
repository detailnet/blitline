<?php

namespace Detail\Blitline\Response;

use Detail\Blitline\Exception;

class JobProcessed extends JobResponse
{
    /**
     * @return array
     */
    public function getImages()
    {
        return array_values($this->getArrayResult('images'));
    }

    /**
     * @return array
     */
    public function getFailedImageIdentifiers()
    {
        return array_values($this->getArrayResult('failed_image_identifiers'));
    }

    /**
     * @return boolean
     */
    public function hasFailedImageIdentifiers()
    {
        return count($this->getFailedImageIdentifiers()) > 0;
    }

    /**
     * @return array
     */
    public function getOriginalMeta()
    {
        return $this->getArrayResult('original_meta');
    }
}
