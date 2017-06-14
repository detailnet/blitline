<?php

namespace DetailTest\Blitline\Job\Definition;

use Detail\Blitline\Job\Definition\FunctionDefinition;
use Detail\Blitline\Job\Definition\JobDefinition;

class JobDefinitionTest extends DefinitionTestCase
{
    /**
     * @return string
     */
    protected function getDefinitionClass()
    {
        return JobDefinition::CLASS;
    }

    public function testSourceCanBeSet()
    {
        $definition = $this->getDefinition();
        $url = 'http://www.detailnet.ch/image.jpg';

        $this->setMethodReturnValue($definition, 'getOption', $url);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSource($url));
        $this->assertEquals($url, $definition->getSource());
    }

    public function testSourceTypeCanBeSet()
    {
        $definition = $this->getDefinition();
        $type = 'http://www.detailnet.ch/image.jpg';

        $this->setMethodReturnValue($definition, 'getOption', $type);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSourceType($type));
        $this->assertEquals($type, $definition->getSourceType());
    }

    public function testSourceDataCanBeSet()
    {
        $definition = $this->getDefinition();
        $data = array(
            'colorspace' => 'rgb',
            'dpi' => 300,
        );

        $this->setMethodReturnValue($definition, 'getOption', $data);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSourceData($data));
        $this->assertEquals($data, $definition->getSourceData());
    }

    public function testPostbackUrlCanBeSet()
    {
        $definition = $this->getDefinition();
        $url = 'http://www.detailnet.ch/job';

        $this->setMethodReturnValue($definition, 'getOption', $url);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setPostbackUrl($url));
        $this->assertEquals($url, $definition->getPostbackUrl());
    }

    public function testVersionCanBeSet()
    {
        $definition = $this->getDefinition();
        $version = '1.2.3';

        $this->setMethodReturnValue($definition, 'getOption', $version);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setVersion($version));
        $this->assertEquals($version, $definition->getVersion());
    }

    public function testFunctionsCanBeSet()
    {
        $definition = $this->getDefinition();
        $functionOne = new FunctionDefinition();
        $functionTwo = new FunctionDefinition();
        $functions = array(
            $functionOne,
            $functionTwo,
        );

        $this->setMethodReturnValue($definition, 'getOption', $functions);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setFunctions(array($functionOne)));
        $this->assertEquals($definition, $definition->addFunction($functionTwo));
        $this->assertEquals($functions, $definition->getFunctions());
    }
}
