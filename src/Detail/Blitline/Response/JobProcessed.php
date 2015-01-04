<?php

namespace  Detail\Blitline\Response;

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
    public function getOriginalMeta()
    {
        return $this->getResult('original_meta');
    }
}
