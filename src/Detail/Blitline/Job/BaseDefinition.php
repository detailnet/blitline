<?php

namespace Detail\Blitline\Job;

abstract class BaseDefinition
{
    protected $options;

    protected function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function toArray()
    {
        /** @todo Instances of BaseDefinition to array */
        return $this->options;
    }
}
