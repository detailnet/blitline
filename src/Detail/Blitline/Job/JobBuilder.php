<?php

namespace Detail\Blitline\Job;

use Detail\Blitline\Exception\RuntimeException;
use Detail\Blitline\Job\Definition\DefinitionInterface;

class JobBuilder implements JobBuilderInterface
{
    /**
     * @var string
     */
    protected $jobClass;

    /**
     * @var string
     */
    protected $functionClass;

    /**
     * @var array
     */
    protected $defaultOptions = array();

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
     * @param DefinitionInterface $definition
     * @return array
     */
    public function getDefaultOptions(DefinitionInterface $definition = null)
    {
        $options = $this->defaultOptions;

        if ($definition === null) {
            return $options;
        }

        $jobInterface      = $this->getDefinitonFqcn('JobDefinitionInterface');
        $functionInterface = $this->getDefinitonFqcn('FunctionDefinitionInterface');

        $prefix = null;
        $prefixSeparator = '.';

        if ($definition instanceof $jobInterface) {
            $prefix = 'job';
        } elseif ($definition instanceof $functionInterface) {
            $prefix = 'function';
        } else {
            return array();
        }

        $keyMatchesPrefix = function($key) use ($prefix, $prefixSeparator) {
            $combinedPrefix = $prefix . $prefixSeparator;

            return (strpos($key, $combinedPrefix) === 0) && (strlen($key) > strlen($combinedPrefix));
        };

        $matchingKeys = array_filter(array_keys($options), $keyMatchesPrefix);

        $matchingOptions = array_intersect_key($options, array_flip($matchingKeys));
        $matchingOptionsWithoutPrefix = array();

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

    public function __construct()
    {
        // Set default definition classes
        $this->setJobClass($this->getDefinitonFqcn('JobDefinition'));
        $this->setFunctionClass($this->getDefinitonFqcn('FunctionDefinition'));
    }

    /**
     * @inheritdoc
     */
    public function createJob()
    {
        return $this->createDefinition(
            $this->getJobClass(),
            $this->getDefinitonFqcn('JobDefinitionInterface')
        );
    }

    /**
     * @inheritdoc
     */
    public function createFunction()
    {
        return $this->createDefinition(
            $this->getFunctionClass(),
            $this->getDefinitonFqcn('FunctionDefinitionInterface')
        );
    }

    /**
     * @param string $class
     * @param string $interface
     * @return DefinitionInterface
     */
    protected function createDefinition($class, $interface)
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf('Class "%s" does not exist', $class));
        }

        /** @var DefinitionInterface $definition */
        $definition = new $class();

        if (!$definition instanceof $interface) {
            throw new RuntimeException(
                sprintf('Definition of class "%s" does not implement "%s"', $class, $interface)
            );
        }

        $definition->applyOptions($this->getDefaultOptions($definition));

        return $definition;
    }

    /**
     * @param string $class
     * @return string
     */
    protected function getDefinitonFqcn($class)
    {
        return __NAMESPACE__ . '\\Definition\\' . $class;
    }
}
