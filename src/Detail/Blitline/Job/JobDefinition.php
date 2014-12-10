<?php

namespace Detail\Blitline\Job;

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

    public function setSourceUrl($url)
    {
        $this->setOption(self::OPTION_SOURCE, $url);
        return $this;
    }

    public function setVersion($version)
    {
        $this->setOption(self::OPTION_VERSION, $version);
        return $this;
    }

    public function setFunctions(array $functions)
    {
        /** @todo Check that array contains valid functions */
        $this->setOption(self::OPTION_FUNCTIONS, $functions);
        return $this;
    }

    public function addFunction($function)
    {
        /** @todo Check that is array or Function object */
        $this->options[self::OPTION_FUNCTIONS][] = $function;
        return $this;
    }
}
