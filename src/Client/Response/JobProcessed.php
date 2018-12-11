<?php

namespace Detail\Blitline\Client\Response;

class JobProcessed extends JobResponse
{
    public function getImages(): array
    {
        return array_values($this->searchArray('images'));
    }

    public function getFailedImageIdentifiers(): array
    {
        return array_values($this->searchArray('failed_image_identifiers'));
    }

    public function hasFailedImageIdentifiers(): bool
    {
        return count($this->getFailedImageIdentifiers()) > 0;
    }

    public function getOriginalMeta(): array
    {
        return $this->searchArray('original_meta');
    }
}
