<?php

namespace Detail\Blitline\Job\Definition;

use Detail\Blitline\Job\Source;

class JobDefinition extends BaseDefinition implements JobDefinitionInterface
{
    const OPTION_SOURCE       = 'src';
    const OPTION_POSTBACK_URL = 'postback_url';
    const OPTION_VERSION      = 'v';
    const OPTION_FUNCTIONS    = 'functions';

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
    public function setSourceUrl($url)
    {
        // Try to set an AWS S3 source first, to allow private access to objects
        try {
            $src = Source\AwsS3Source::fromUrl($url);
        } catch (\Exception $e) {
            $src = new Source\UrlSource($url);
        }

        $this->setOption(self::OPTION_SOURCE, $src);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSourceUrl()
    {
        return $this->getOption(self::OPTION_SOURCE)->getUrl();
    }

    /**
     * @inheritdoc
     */
    public function setSource(SourceInterface $src)
    {
        $this->setOption(self::OPTION_SOURCE, $src);
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
