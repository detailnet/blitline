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
        $src = array(
            'name' => 's3',
            'bucket' => 'curzio-della-santa',
            'key' => 'test-files/Desert.jpg',
        );

        $this->setMethodReturnValue($definition, 'getOption', $src);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSource($src));
        $this->assertEquals($src, $definition->getSource());
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
