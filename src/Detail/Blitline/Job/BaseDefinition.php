<?php

namespace Detail\Blitline\Job;

use ArrayObject;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

abstract class BaseDefinition
{
    protected $options = array();

    /**
     * @inheritdoc
     */
    public function applyOptions(array $options)
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
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

            // Apply changes value
            if ($value != $dataIterator->current()) {
                /** @var RecursiveArrayIterator $innerIterator */
                $innerIterator = $dataIterator->getInnerIterator();
                $innerIterator->offsetSet($key, $value);
            }
        }

        // Helper function which recursively converts to a normal (recursive) array again
        $toArray = function($data) use (&$toArray) { // Reference is required for "recursive closure"...
            if ($data instanceof ArrayObject) {
                $data = (array) $data;
            }

            return is_array($data) ? array_map($toArray, $data) : $data;
        };

        return $toArray($data);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    protected function setOption($name, $value)
    {
        // Merge if both existing and new option value are arrays...
        if (is_array($value) && isset($this->options[$name]) && is_array($this->options[$name])) {
            $value = array_merge_recursive($this->options[$name], $value);
        }

        $this->options[$name] = $value;
    }
}
