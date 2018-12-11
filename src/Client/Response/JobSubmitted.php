<?php

namespace Detail\Blitline\Client\Response;

class JobSubmitted extends JobResponse
{
    public function getImages(): array
    {
        return array_values($this->searchArray('images'));
    }
}
