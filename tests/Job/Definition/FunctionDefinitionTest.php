<?php

namespace DetailTest\Blitline\Job\Definition;

use Detail\Blitline\Job\Definition\FunctionDefinition;

class FunctionDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass(): string
    {
        return FunctionDefinition::CLASS;
    }

    public function testNameCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $name = 'resize';

        $this->setMethodReturnValue($definition, 'getOption', $name);

        /** @var FunctionDefinition $definition */

        $this->assertEquals($definition, $definition->setName($name));
        $this->assertEquals($name, $definition->getName());
    }

    public function testParamsCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $params = ['a' => 'b'];

        $this->setMethodReturnValue($definition, 'getOption', $params);

        /** @var FunctionDefinition $definition */

        $this->assertEquals($definition, $definition->setParams($params));
        $this->assertEquals($params, $definition->getParams());
    }

    public function testSaveOptionsCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $saveOptions = ['a' => 'b'];

        $this->setMethodReturnValue($definition, 'getOption', $saveOptions);

        /** @var FunctionDefinition $definition */

        $this->assertEquals($definition, $definition->setSaveOptions($saveOptions));
        $this->assertEquals($saveOptions, $definition->getSaveOptions());
    }
}
