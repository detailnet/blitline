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
     * @param array $options
     * @return JobBuilder
     */
    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = $options;
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

        $jobInterface         = $this->getDefinitonFqcn('JobDefinitionInterface');
        $jobFunctionInterface = $this->getDefinitonFqcn('FunctionDefinitionInterface');

        $prefix = null;
        $prefixSeparator = '.';

        if ($definition instanceof $jobInterface) {
            $prefix = 'job';
        } elseif ($definition instanceof $jobFunctionInterface) {
            $prefix = 'function';
        } else {
            return $options;
        }

        $keyMatchesPrefix = function($key) use ($prefix, $prefixSeparator) {
            return strpos($key, $prefix . $prefixSeparator) === 0;
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

    public function __construct()
    {
        $this->jobClass      = $this->getDefinitonFqcn('JobDefinition');
        $this->functionClass = $this->getDefinitonFqcn('FunctionDefinition');
    }

    /**
     * @inheritdoc
     */
    public function createJob()
    {
        return $this->createDefinition(
            $this->jobClass,
            $this->getDefinitonFqcn('JobDefinitionInterface')
        );
    }

    /**
     * @inheritdoc
     */
    public function createFunction()
    {
        return $this->createDefinition(
            $this->functionClass,
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
