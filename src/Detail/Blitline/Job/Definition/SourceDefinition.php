<?php

namespace Detail\Blitline\Job\Definition;

class SourceDefinition extends BaseDefinition implements
    SourceDefinitionInterface
{
    const OPTION_NAME   = 'name';
    const OPTION_BUCKET = 'bucket';
    const OPTION_KEY    = 'key';

    /**
     * @var array
     */
    protected $options = array(
        self::OPTION_NAME => 's3',
    );

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->setOption(self::OPTION_NAME, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getOption(self::OPTION_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setBucket($bucket)
    {
        $this->setOption(self::OPTION_BUCKET, $bucket);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBucket()
    {
        return $this->getOption(self::OPTION_BUCKET);
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->setOption(self::OPTION_KEY, $key);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->getOption(self::OPTION_KEY);
    }
}
