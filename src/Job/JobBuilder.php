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

    public function getJobClass(): string
    {
        return $this->jobClass;
    }

    public function setJobClass(string $jobClass): JobBuilder
    {
        $this->jobClass = $jobClass;
        return $this;
    }

    public function getSourceClass(): string
    {
        return $this->sourceClass;
    }

    public function setSourceClass(string $sourceClass): JobBuilder
    {
        $this->sourceClass = $sourceClass;
        return $this;
    }

    public function getFunctionClass(): string
    {
        return $this->functionClass;
    }

    public function setFunctionClass(string $functionClass): JobBuilder
    {
        $this->functionClass = $functionClass;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getDefaultOption(string $name, $default = null)
    {
        return array_key_exists($name, $this->defaultOptions) ? $this->defaultOptions[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return JobBuilder
     */
    public function setDefaultOption(string $name, $value): JobBuilder
    {
        $this->defaultOptions[$name] = $value;
        return $this;
    }

    public function getDefaultOptions(Definition\DefinitionInterface $definition = null): array
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

    public function setDefaultOptions(array $options): JobBuilder
    {
        $this->defaultOptions = $options;
        return $this;
    }

    public function createJob(): Definition\JobDefinitionInterface
    {
        /** @var Definition\JobDefinitionInterface $definition */
        $definition = $this->createDefinition(
            $this->getJobClass(),
            Definition\JobDefinitionInterface::CLASS
        );

        return $definition;
    }

    public function createSource(): Definition\SourceDefinitionInterface
    {
        /** @var Definition\SourceDefinitionInterface $definition */
        $definition = $this->createDefinition(
            $this->getSourceClass(),
            Definition\SourceDefinitionInterface::CLASS
        );

        return $definition;
    }

    public function createFunction(): Definition\FunctionDefinitionInterface
    {
        /** @var Definition\FunctionDefinitionInterface $definition */
        $definition = $this->createDefinition(
            $this->getFunctionClass(),
            Definition\FunctionDefinitionInterface::CLASS
        );

        return $definition;
    }

    private function createDefinition(string $class, string $interface): Definition\DefinitionInterface
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
