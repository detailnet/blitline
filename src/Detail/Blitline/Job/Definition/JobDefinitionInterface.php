<?php

namespace Detail\Blitline\Job\Definition;

interface JobDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $url
     * @return JobDefinitionInterface
     */
    public function setSourceUrl($url);

    /**
     * @param string $version
     * @return JobDefinitionInterface
     */
    public function setVersion($version);

    /**
     * @param array|FunctionDefinitionInterface[] $functions
     * @return JobDefinitionInterface
     */
    public function setFunctions(array $functions);

    /**
     * @param array|FunctionDefinitionInterface $function
     * @return JobDefinitionInterface
     */
    public function addFunction($function);
}
