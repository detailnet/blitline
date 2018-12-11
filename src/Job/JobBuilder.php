<?php

namespace Detail\Blitline\Job;

use Detail\Blitline\Exception\RuntimeException;

class JobBuilder implements
    JobBuilderInterface
{
    /**
     * @var string
     */
    protected $jobClass = Definition\JobDefinition::CLASS;

    /**
     * @var string
     */
    protected $sourceClass = Definition\SourceDefinition::CLASS;

    /**
     * @var string
     */
    protected $functionClass = Definition\FunctionDefinition::CLASS;

    /**
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * @return string
     */
    public function getJobClass()
    {
        return $this->jobClass;
    }

    /**
     * @param string $jobClass
     * @return JobBuilder
     */
    public function setJobClass($jobClass)
    {
        $this->jobClass = $jobClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceClass()
    {
        return $this->sourceClass;
    }

    /**
     * @param string $sourceClass
     * @return JobBuilder
     */
    public function setSourceClass($sourceClass)
    {
        $this->sourceClass = $sourceClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getFunctionClass()
    {
        return $this->functionClass;
    }

    /**
     * @param string $functionClass
     * @return JobBuilder
     */
    public function setFunctionClass($functionClass)
    {
        $this->functionClass = $functionClass;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getDefaultOption($name, $default = null)
    {
        return array_key_exists($name, $this->defaultOptions) ? $this->defaultOptions[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return JobBuilder
     */
    public function setDefaultOption($name, $value)
    {
        $this->defaultOptions[$name] = $value;
        return $this;
    }

    /**
     * @param Definition\DefinitionInterface $definition
     * @return array
     */
    public function getDefaultOptions(Definition\DefinitionInterface $definition = null)
    {
        $options = $this->defaultOptions;

        if ($definition === null) {
            return $options;
        }

        $prefix = null;
        $prefixSeparator = '.';

        if ($definition instanceof Definition\JobDefinitionInterface) {
            $prefix = 'job';
        } elseif ($definition instanceof Definition\FunctionDefinitionInterface) {
            $prefix = 'function';
        } else {
            return [];
        }

        $keyMatchesPrefix = function ($key) use ($prefix, $prefixSeparator) {
            $combinedPrefix = $prefix . $prefixSeparator;

            return (strpos($key, $combinedPrefix) === 0) && (strlen($key) > strlen($combinedPrefix));
        };

        $matchingKeys = array_filter(array_keys($options), $keyMatchesPrefix);

        $matchingOptions = array_intersect_key($options, array_flip($matchingKeys));
        $matchingOptionsWithoutPrefix = [];

        $prefixLength = strlen($prefix) + strlen($prefixSeparator);

        foreach ($matchingOptions as $optionName => $optionsValue) {
            $matchingOptionsWithoutPrefix[substr($optionName, $prefixLength)] = $optionsValue;
        }

        return $matchingOptionsWithoutPrefix;
    }

    /**
     * @param array $options
     * @return JobBuilder
     */
    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = $options;
        return $this;
    }

    /**
     * @return Definition\JobDefinitionInterface
     */
    public function createJob()
    {
        return $this->createDefinition(
            $this->getJobClass(),
            Definition\JobDefinitionInterface::CLASS
        );
    }

    /**
     * @return Definition\SourceDefinitionInterface
     */
    public function createSource()
    {
        return $this->createDefinition(
            $this->getSourceClass(),
            Definition\SourceDefinitionInterface::CLASS
        );
    }

    /**
     * @return Definition\FunctionDefinitionInterface
     */
    public function createFunction()
    {
        return $this->createDefinition(
            $this->getFunctionClass(),
            Definition\FunctionDefinitionInterface::CLASS
        );
    }

    /**
     * @param string $class
     * @param string $interface
     * @return Definition\DefinitionInterface
     */
    protected function createDefinition($class, $interface)
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf('Class "%s" does not exist', $class));
        }

        /** @var Definition\DefinitionInterface $definition */
        $definition = new $class();

        if (!$definition instanceof $interface) {
            throw new RuntimeException(
                sprintf('Definition of class "%s" does not implement "%s"', $class, $interface)
            );
        }

        $definition->applyOptions($this->getDefaultOptions($definition));

        return $definition;
    }
}
