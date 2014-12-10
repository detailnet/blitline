<?php

namespace Detail\Blitline\Job;

interface JobBuilderInterface
{
    /**
     * @return JobDefinitionInterface
     */
    public function createJob();

    /**
     * @return JobFunctionDefinitionInterface
     */
    public function createFunction();
}
