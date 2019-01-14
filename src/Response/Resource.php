<?php

namespace Detail\Blitline\Response;

use JmesPath\Env as JmesPath;

use Detail\Blitline\Exception;

class Resource implements
    \ArrayAccess
{
    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @param string $expression
     * @return mixed|null
     */
    public function search(string $expression)
    {
        return JmesPath::search($expression, $this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function __toString(): string
    {
        $jsonData = json_encode($this->data, JSON_PRETTY_PRINT);

        return <<<EOT
Model Data
----------
Data can be retrieved from the model object using the get() method of the
model (e.g., `\$resource->get(\$key)`) or "accessing the result like an
associative array (e.g. `\$resource['key']`). You can also execute JMESPath
expressions on the result data using the search() method.

{$jsonData}

EOT;
    }

    /**
     * @param string|integer $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string|integer $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception\RuntimeException(
            sprintf('Resource is read-only; cannot set "%s"', $offset)
        );
    }

    /**
     * @param string|integer $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param string|integer $offset
     */
    public function offsetUnset($offset)
    {
        throw new Exception\RuntimeException(
            sprintf('Resource is read-only; cannot unset "%s"', $offset)
        );
    }
}
