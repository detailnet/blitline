<?php

namespace Detail\Blitline\Response;

class JobSubmitted extends JobResponse
{
    /**
     * @return array
     */
    public function getImages()
    {
        return array_values($this->getArrayResult('images'));
    }
}
