<?php

namespace Detail\Blitline\Job\Definition;

interface FunctionDefinitionInterface extends DefinitionInterface
{
    public function setName(string $name): FunctionDefinitionInterface;

    public function getName(): ?string;

    public function setParams(array $params): FunctionDefinitionInterface;

    public function getParams(): ?array;

    public function setSaveOptions(array $saveOptions): FunctionDefinitionInterface;

    public function getSaveOptions(): ?array;
}
