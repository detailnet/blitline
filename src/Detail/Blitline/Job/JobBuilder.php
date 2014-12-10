<?php

namespace Detail\Blitline\Job;


use Detail\Blitline\Exception\RuntimeException;

class JobBuilder
    implements JobBuilderInterface
{
    protected $jobClass;

    protected $functionClass;

    protected $defaultOptions = array();

    /**
     * @inheritdoc
     */
    public function addDefaultOption($name, $value)
    {
        $this->defaultOptions[$name] = $value;
    }

    public function __construct()
    {
        $this->jobClass      = __NAMESPACE__ . '\JobDefinition';
        $this->functionClass = __NAMESPACE__ . '\JobFunctionDefinition';
    }

    /**
     * @inheritdoc
     */
    public function createJob()
    {
        return $this->createClass($this->jobClass);
    }

    /**
     * @inheritdoc
     */
    public function createFunction()
    {
        return $this->createClass($this->functionClass);
    }

    protected function createClass($class)
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf('Job class "%s" does not exist', $class));
        }

        return new $class();
    }
}
