<?php

namespace Detail\Blitline\Job\Definition;

class JobDefinition extends BaseDefinition implements
    JobDefinitionInterface
{
    const OPTION_SOURCE       = 'src';
    const OPTION_SOURCE_TYPE  = 'src_type';
    const OPTION_SOURCE_DATA  = 'src_data';
    const OPTION_POSTBACK_URL = 'postback_url';
    const OPTION_VERSION      = 'v';
    const OPTION_PRE_PROCESS  = 'pre_process';
    const OPTION_FUNCTIONS    = 'functions';

    /**
     * @var array
     */
    protected $options = [
        self::OPTION_VERSION   => '1.22',
        self::OPTION_FUNCTIONS => [],
    ];

    /**
     * @param string|SourceDefinitionInterface $src
     * @return JobDefinitionInterface
     */
    public function setSource($src): JobDefinitionInterface
    {
        $this->setOption(self::OPTION_SOURCE, $src);

//        $path = null;
//
//        if ($src instanceof SourceDefinition) {
//            $path = $src->getKey();
//        } elseif (is_string($src)) {
//            $path = parse_url($src, PHP_URL_PATH);
//        }

        return $this;
    }

    /**
     * @return string|SourceDefinitionInterface
     */
    public function getSource()
    {
        return $this->getOption(self::OPTION_SOURCE);
    }

    public function setSourceType(string $sourceType): JobDefinition
    {
        /** @todo Why is this method not in JobDefinitionInterface? */
        $this->setOption(self::OPTION_SOURCE_TYPE, $sourceType);
        return $this;
    }

    public function getSourceType(): ?string
    {
        /** @todo Why is this method not in JobDefinitionInterface? */
        return $this->getOption(self::OPTION_SOURCE_TYPE);
    }

    public function setSourceData(array $sourceData): JobDefinition
    {
        /** @todo Why is this method not in JobDefinitionInterface? */
        $this->setOption(self::OPTION_SOURCE_DATA, $sourceData);
        return $this;
    }

    public function getSourceData(): ?array
    {
        /** @todo Why is this method not in JobDefinitionInterface? */
        return $this->getOption(self::OPTION_SOURCE_DATA);
    }

    public function setPostbackUrl(string $url): JobDefinitionInterface
    {
        $this->setOption(self::OPTION_POSTBACK_URL, $url);
        return $this;
    }

    public function getPostbackUrl(): ?string
    {
        return $this->getOption(self::OPTION_POSTBACK_URL);
    }

    public function setVersion(string $version): JobDefinitionInterface
    {
        $this->setOption(self::OPTION_VERSION, $version);
        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->getOption(self::OPTION_VERSION);
    }

    public function setPreProcess(array $preProcess): JobDefinitionInterface
    {
        /** @todo Check that array contains valid preProcess */
        $this->setOption(self::OPTION_PRE_PROCESS, $preProcess);
        return $this;
    }

    public function getPreProcess(): ?array
    {
        return $this->getOption(self::OPTION_PRE_PROCESS);
    }

    /**
     * @param array|FunctionDefinitionInterface[] $functions
     * @return JobDefinitionInterface
     */
    public function setFunctions(array $functions): JobDefinitionInterface
    {
        /** @todo Check that array contains valid functions */
        $this->setOption(self::OPTION_FUNCTIONS, $functions);
        return $this;
    }

    /**
     * @return array|FunctionDefinitionInterface[]
     */
    public function getFunctions(): ?array
    {
        return $this->getOption(self::OPTION_FUNCTIONS);
    }

    /**
     * @param array|FunctionDefinitionInterface $function
     * @return JobDefinitionInterface
     */
    public function addFunction($function): JobDefinitionInterface
    {
        /** @todo Check that is array or Function object */
        $this->setFunctions([$function]); // Will get merged with existing functions
        return $this;
    }
}
