<?php

namespace Detail\Blitline\Response;

use Detail\Blitline\Exception;

class JobProcessed extends BaseResponse
{
    /**
     * @return array
     */
    public function getImages()
    {
        return $this->getResult('images');
    }

    /**
     * @return array
     */
    public function getFailedImageIdentifiers()
    {
        return $this->getArrayResult('failed_image_identifiers');
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
