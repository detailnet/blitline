<?php

namespace DetailTest\Blitline\Job\Definition;

use Detail\Blitline\Job\Definition\FunctionDefinition;
use Detail\Blitline\Job\Definition\JobDefinition;

class JobDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass(): string
    {
        return JobDefinition::CLASS;
    }

    public function testSourceCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $url = 'http://www.detailnet.ch/image.jpg';

        $this->setMethodReturnValue($definition, 'getOption', $url);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSource($url));
        $this->assertEquals($url, $definition->getSource());
    }

    public function testSourceTypeCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $type = 'http://www.detailnet.ch/image.jpg';

        $this->setMethodReturnValue($definition, 'getOption', $type);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSourceType($type));
        $this->assertEquals($type, $definition->getSourceType());
    }

    public function testSourceDataCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $data = [
            'colorspace' => 'rgb',
            'dpi' => 300,
        ];

        $this->setMethodReturnValue($definition, 'getOption', $data);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSourceData($data));
        $this->assertEquals($data, $definition->getSourceData());
    }

    public function testPostbackUrlCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $url = 'http://www.detailnet.ch/job';

        $this->setMethodReturnValue($definition, 'getOption', $url);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setPostbackUrl($url));
        $this->assertEquals($url, $definition->getPostbackUrl());
    }

    public function testVersionCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $version = '1.2.3';

        $this->setMethodReturnValue($definition, 'getOption', $version);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setVersion($version));
        $this->assertEquals($version, $definition->getVersion());
    }

    public function testFunctionsCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $functionOne = new FunctionDefinition();
        $functionTwo = new FunctionDefinition();
        $functions = [
            $functionOne,
            $functionTwo,
        ];

        $this->setMethodReturnValue($definition, 'getOption', $functions);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setFunctions([$functionOne]));
        $this->assertEquals($definition, $definition->addFunction($functionTwo));
        $this->assertEquals($functions, $definition->getFunctions());
    }
}
