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
     * @return string
     */
    public function getName();

    /**
     * @param array $params
     * @return FunctionDefinitionInterface
     */
    public function setParams(array $params);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $saveOptions
     * @return FunctionDefinitionInterface
     */
    public function setSaveOptions(array $saveOptions);

    /**
     * @return array
     */
    public function getSaveOptions();
}
