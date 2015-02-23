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
        try {
            $imageIdentifiers = $this->getResult('failed_image_identifiers');
        } catch (Exception\ResponseException $e) {
            return array();
        }

        if (!is_array($imageIdentifiers)) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Invalid value for "failed_image_identifiers"; expected array but got %s',
                    is_object($imageIdentifiers) ? get_class($imageIdentifiers) : gettype($imageIdentifiers)
                )
            );
        }

        return $imageIdentifiers;
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
        return $this->getResult('original_meta');
    }
}
