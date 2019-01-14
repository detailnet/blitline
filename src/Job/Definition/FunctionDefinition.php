<?php

namespace Detail\Blitline\Job\Definition;

class FunctionDefinition extends BaseDefinition implements
    FunctionDefinitionInterface
{
    const OPTION_NAME         = 'name';
    const OPTION_PARAMS       = 'params';
    const OPTION_SAVE_OPTIONS = 'save';

    public function setName(string $name): FunctionDefinitionInterface
    {
        $this->setOption(self::OPTION_NAME, $name);
        return $this;
    }

    public function getName(): ?string
    {
        return $this->getOption(self::OPTION_NAME);
    }

    public function setParams(array $params): FunctionDefinitionInterface
    {
        $this->setOption(self::OPTION_PARAMS, $params);
        return $this;
    }

    public function getParams(): ?array
    {
        return $this->getOption(self::OPTION_PARAMS);
    }

    public function setSaveOptions(array $saveOptions): FunctionDefinitionInterface
    {
        $this->setOption(self::OPTION_SAVE_OPTIONS, $saveOptions);
        return $this;
    }

    public function getSaveOptions(): ?array
    {
        return $this->getOption(self::OPTION_SAVE_OPTIONS);
    }
}
