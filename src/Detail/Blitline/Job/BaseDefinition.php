<?php

namespace Detail\Blitline\Job;

use ArrayObject;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

abstract class BaseDefinition
{
    protected $options;

    protected function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @return array
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
}
