<?php

namespace Detail\Blitline\Job\Definition;

interface SourceInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getUrl();
}
