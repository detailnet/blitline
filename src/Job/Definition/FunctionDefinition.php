<?php

namespace Detail\Blitline\Job\Definition;

class FunctionDefinition extends BaseDefinition implements FunctionDefinitionInterface
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
    public function getName()
    {
        return $this->getOption(self::OPTION_NAME);
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
    public function getParams()
    {
        return $this->getOption(self::OPTION_PARAMS);
    }

    /**
     * @inheritdoc
     */
    public function setSaveOptions(array $saveOptions)
    {
        $this->setOption(self::OPTION_SAVE_OPTIONS, $saveOptions);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSaveOptions()
    {
        return $this->getOption(self::OPTION_SAVE_OPTIONS);
    }
}
