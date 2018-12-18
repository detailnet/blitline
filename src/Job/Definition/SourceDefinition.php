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
    protected $options = [
        self::OPTION_NAME => 's3',
    ];

    public function setName(string $name): SourceDefinitionInterface
    {
        $this->setOption(self::OPTION_NAME, $name);
        return $this;
    }

    public function getName(): ?string
    {
        return $this->getOption(self::OPTION_NAME);
    }

    public function setBucket(string $bucket): SourceDefinitionInterface
    {
        $this->setOption(self::OPTION_BUCKET, $bucket);
        return $this;
    }

    public function getBucket(): ?string
    {
        return $this->getOption(self::OPTION_BUCKET);
    }

    public function setKey(string $key): SourceDefinitionInterface
    {
        $this->setOption(self::OPTION_KEY, $key);
        return $this;
    }

    public function getKey(): ?string
    {
        return $this->getOption(self::OPTION_KEY);
    }
}
