<?php

namespace Detail\Blitline\Job\Definition;

interface JobDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string|SourceDefinitionInterface $src
     * @return JobDefinitionInterface
     */
    public function setSource($src): JobDefinitionInterface;

    /**
     * @return string|SourceDefinitionInterface
     */
    public function getSource();

    public function setPostbackUrl(string $url): JobDefinitionInterface;

    public function getPostbackUrl(): ?string;

    public function setVersion(string $version): JobDefinitionInterface;

    public function getVersion(): ?string;

    public function setPreProcess(array $preProcess): JobDefinitionInterface;

    public function getPreProcess(): ?array;

    /**
     * @param array|FunctionDefinitionInterface[] $functions
     * @return JobDefinitionInterface
     */
    public function setFunctions(array $functions): JobDefinitionInterface;

    /**
     * @return array|FunctionDefinitionInterface[]
     */
    public function getFunctions(): ?array;

    /**
     * @param array|FunctionDefinitionInterface $function
     * @return JobDefinitionInterface
     */
    public function addFunction($function): JobDefinitionInterface;
}
