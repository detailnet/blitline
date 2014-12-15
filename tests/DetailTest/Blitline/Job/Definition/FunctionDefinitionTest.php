<?php

namespace DetailTest\Blitline\Job\Definition;

use Detail\Blitline\Job\Definition\FunctionDefinition;

class FunctionDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass()
    {
        return 'Detail\Blitline\Job\Definition\FunctionDefinition';
    }

    public function testNameCanBeSet()
    {
        $definition = $this->getDefinition();
        $name = 'resize';

        $this->setMethodReturnValue($definition, 'getOption', $name);

        /** @var FunctionDefinition $definition */

        $this->assertEquals($definition, $definition->setName($name));
        $this->assertEquals($name, $definition->getName());
    }

    public function testParamsCanBeSet()
    {
        $definition = $this->getDefinition();
        $params = array('a' => 'b');

        $this->setMethodReturnValue($definition, 'getOption', $params);

        /** @var FunctionDefinition $definition */

        $this->assertEquals($definition, $definition->setParams($params));
        $this->assertEquals($params, $definition->getParams());
    }

    public function testSaveOptionsCanBeSet()
    {
        $definition = $this->getDefinition();
        $saveOptions = array('a' => 'b');

        $this->setMethodReturnValue($definition, 'getOption', $saveOptions);

        /** @var FunctionDefinition $definition */

        $this->assertEquals($definition, $definition->setSaveOptions($saveOptions));
        $this->assertEquals($saveOptions, $definition->getSaveOptions());
    }
}
