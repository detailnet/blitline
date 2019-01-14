<?php

namespace Detail\Blitline\Job\Definition;

interface DefinitionInterface
{
    public function applyOptions(array $options): DefinitionInterface;

    public function toArray(): array;
}
