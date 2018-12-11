<?php

namespace Detail\Blitline\Job\Definition;

interface DefinitionInterface
{
    /**
     * @param array $options
     * @return DefinitionInterface
     */
    public function applyOptions(array $options);

    /**
     * @return array
     */
    public function toArray();
}
