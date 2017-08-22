<?php

namespace Detail\Blitline\Job\Definition;

class JobDefinition extends BaseDefinition implements JobDefinitionInterface
{
    const OPTION_SOURCE       = 'src';
    const OPTION_POSTBACK_URL = 'postback_url';
    const OPTION_VERSION      = 'v';
    const OPTION_PRE_PROCESS  = 'pre_process';
    const OPTION_FUNCTIONS    = 'functions';
    const OPTION_SOURCE_TYPE  = 'src_type';
    const OPTION_SOURCE_DATA  = 'src_data';

    /**
     * @var array
     */
    protected $options = array(
        self::OPTION_VERSION   => '1.22',
        self::OPTION_FUNCTIONS => array(),
    );

    /**
     * @inheritdoc
     */
    public function setSource($src)
    {
        $this->setOption(self::OPTION_SOURCE, $src);

        $path = null;

        if ($src instanceof SourceDefinition) {
            $path = $src->getKey();
        } elseif (is_string($src)) {
            $path = parse_url($src, PHP_URL_PATH);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSource()
    {
        return $this->getOption(self::OPTION_SOURCE);
    }

    /**
     * @param string $sourceType
     * @return JobDefinitionInterface
     */
    public function setSourceType($sourceType)
    {
        $this->setOption(self::OPTION_SOURCE_TYPE, $sourceType);
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceType()
    {
        return $this->getOption(self::OPTION_SOURCE_TYPE);
    }

    /**
     * @param array $sourceData
     * @return JobDefinitionInterface
     */
    public function setSourceData($sourceData)
    {
        $this->setOption(self::OPTION_SOURCE_DATA, $sourceData);
        return $this;
    }

    /**
     * @return array
     */
    public function getSourceData()
    {
        return $this->getOption(self::OPTION_SOURCE_DATA);
    }

    /**
     * @param string $url
     * @return JobDefinitionInterface
     */
    public function setPostbackUrl($url)
    {
        $this->setOption(self::OPTION_POSTBACK_URL, $url);
        return $this;
    }

    /**
     * @return string
     */
    public function getPostbackUrl()
    {
        return $this->getOption(self::OPTION_POSTBACK_URL);
    }

    /**
     * @inheritdoc
     */
    public function setVersion($version)
    {
        $this->setOption(self::OPTION_VERSION, $version);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return $this->getOption(self::OPTION_VERSION);
    }

    /**
     * @inheritdoc
     */
    public function setPreProcess(array $preProcess)
    {
        /** @todo Check that array contains valid preProcess */
        $this->setOption(self::OPTION_PRE_PROCESS, $preProcess);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPreProcess()
    {
        return $this->getOption(self::OPTION_PRE_PROCESS);
    }

    /**
     * @inheritdoc
     */
    public function setFunctions(array $functions)
    {
        /** @todo Check that array contains valid functions */
        $this->setOption(self::OPTION_FUNCTIONS, $functions);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return $this->getOption(self::OPTION_FUNCTIONS);
    }

    /**
     * @inheritdoc
     */
    public function addFunction($function)
    {
        /** @todo Check that is array or Function object */
        $this->setFunctions(array($function)); // Will get merged with existing functions
        return $this;
    }
}
