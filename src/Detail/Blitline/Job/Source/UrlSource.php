<?php

namespace Detail\Blitline\Job\Source;

class UrlSource extends BaseSource
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct(static::TYPE_URL);

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getValue();
    }
}
