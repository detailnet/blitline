<?php

namespace Detail\Blitline\Job\Definition;

class FunctionDefinition extends BaseDefinition implements FunctionDefinitionInterface
{
    const OPTION_NAME         = 'name';
    const OPTION_PARAMS       = 'params';
    const OPTION_SAVE_OPTIONS = 'save';

//    protected $options = array(
//        'save' => array(
//            's3_destination' => array(
//                'xxx' => 'xxx',
//            ),
//            'yyy' => 'yyy',
//        ),
//    );

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
