<?php

namespace Detail\Blitline\Job\Definition;

interface SourceDefinitionInterface extends DefinitionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return SourceDefinitionInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getBucket();

    /**
     * @param string $bucket
     * @return SourceDefinitionInterface
     */
    public function setBucket($bucket);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     * @return SourceDefinitionInterface
     */
    public function setKey($key);
}
