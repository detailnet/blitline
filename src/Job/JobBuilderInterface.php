<?php

namespace Detail\Blitline\Job;

interface JobBuilderInterface
{
    public function createJob(): Definition\JobDefinitionInterface;

    public function createSource(): Definition\SourceDefinitionInterface;

    public function createFunction(): Definition\FunctionDefinitionInterface;
}
