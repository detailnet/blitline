<?php

namespace Detail\Blitline\Job\Source;

use Detail\Blitline\Job\Definition;

abstract class BaseSource implements
    Definition\SourceInterface
{
    /**
     * @var string
     */
    protected $type = self::TYPE_URL;

    /**
     * @param string $type
     */
    public function __construct($type = null)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
