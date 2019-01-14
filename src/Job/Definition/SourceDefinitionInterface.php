<?php

namespace Detail\Blitline\Job\Definition;

interface SourceDefinitionInterface extends DefinitionInterface
{
    public function getName(): ?string;

    public function setName(string $name): SourceDefinitionInterface;

    public function getBucket(): ?string;

    public function setBucket(string $bucket): SourceDefinitionInterface;

    public function getKey(): ?string;

    public function setKey(string $key): SourceDefinitionInterface;
}
