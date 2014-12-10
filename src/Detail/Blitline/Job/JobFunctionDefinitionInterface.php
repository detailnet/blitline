<?php

namespace Detail\Blitline\Job;

interface JobFunctionDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $name
     * @return JobFunctionDefinitionInterface
     */
    public function setName($name);

    /**
     * @param array $params
     * @return JobFunctionDefinitionInterface
     */
    public function setParams(array $params);

    /**
     * @param array $saveOptions
     * @return JobFunctionDefinitionInterface
     */
    public function setSaveOptions(array $saveOptions);
}
