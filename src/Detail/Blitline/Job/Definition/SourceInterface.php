<?php

namespace Detail\Blitline\Job\Definition;

interface SourceInterface
{
    const TYPE_URL = 'url';

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getUrl();
}
