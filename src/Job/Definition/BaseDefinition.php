<?php

namespace Detail\Blitline\Job\Definition;

use ArrayObject;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

abstract class BaseDefinition implements
    DefinitionInterface
{
    /**
     * @var array
     */
    protected $options = [];

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): BaseDefinition
    {
        $this->options = $options;
        return $this;
    }

    public function applyOptions(array $options): DefinitionInterface
    {
        $this->options = array_replace_recursive($this->options, $options);
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return BaseDefinition
     */
    public function setOption(string $name, $value): BaseDefinition
    {
        // Merge if both existing and new option value are arrays...
        if (is_array($value) && isset($this->options[$name]) && is_array($this->options[$name])) {
            $value = array_replace_recursive($this->options[$name], $value);
        }

        $this->options[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    public function toArray(): array
    {
        $data = new ArrayObject($this->options);

        $dataIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($data),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($dataIterator as $key => $value) {
            if ($value instanceof self) {
                $value = $value->toArray();
            }

            if (is_array($value)) {
                $value = new ArrayObject($value);
            }

            // Apply changed value
            if ($value != $dataIterator->current()) {
                /** @var RecursiveArrayIterator $innerIterator */
                $innerIterator = $dataIterator->getInnerIterator();
                $innerIterator->offsetSet($key, $value);
            }
        }

        // Helper function which recursively converts to a normal (recursive) array again.
        // Reference is required for "recursive closure"...
        $toArray = function ($data) use (&$toArray) {
            if ($data instanceof ArrayObject) {
                $data = (array) $data;
            }

            return is_array($data) ? array_map($toArray, $data) : $data;
        };

        return $toArray($data);
    }
}
