<?php

namespace Detail\Blitline\Job\Definition;

class JobDefinition extends BaseDefinition implements JobDefinitionInterface
{
    const OPTION_SOURCE       = 'src';
    const OPTION_POSTBACK_URL = 'postback_url';
    const OPTION_VERSION      = 'v';
    const OPTION_FUNCTIONS    = 'functions';

    protected $options = array(
        self::OPTION_VERSION   => '1.21',
        self::OPTION_FUNCTIONS => array(),
    );

    /**
     * @inheritdoc
     */
    public function setSourceUrl($url)
    {
        $this->setOption(self::OPTION_SOURCE, $url);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSourceUrl()
    {
        return $this->getOption(self::OPTION_SOURCE);
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
