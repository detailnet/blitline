<?php

namespace Detail\Blitline\Job\Definition;

interface FunctionDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $name
     * @return FunctionDefinitionInterface
     */
    public function setName($name);

    /**
     * @param array $params
     * @return FunctionDefinitionInterface
     */
    public function setParams(array $params);

    /**
     * @param array $saveOptions
     * @return FunctionDefinitionInterface
     */
    public function setSaveOptions(array $saveOptions);
}
