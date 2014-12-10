<?php

namespace Detail\Blitline\Job;

class JobFunctionDefinition extends BaseDefinition
    implements JobFunctionDefinitionInterface
{
    const OPTION_NAME         = 'name';
    const OPTION_PARAMS       = 'params';
    const OPTION_SAVE_OPTIONS = 'save';

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
    public function setParams(array $params)
    {
        $this->setOption(self::OPTION_PARAMS, $params);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSaveOptions(array $saveOptions)
    {
        $this->setOption(self::OPTION_SAVE_OPTIONS, $saveOptions);
        return $this;
    }
}
