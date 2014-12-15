<?php

namespace DetailTest\Blitline\Job\Definition;

use RuntimeException;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

abstract class DefinitionTestCase extends TestCase
{
    /**
     * @var MockObject
     */
    protected $definition;

    /**
     * @return string
     */
    abstract protected function getDefinitionClass();

    protected function setUp()
    {
        $definitionClass = $this->getDefinitionClass();

        if (!class_exists($definitionClass)) {
            throw new RuntimeException(
                sprintf('Definition class "%s" does not exist', $definitionClass)
            );
        }

        $this->definition = $this->getMock(
            $definitionClass,
            array('setOption', 'getOption')
        );

        $this->setMethodReturnValue($this->definition, 'setOption');
    }

    /**
     * @return MockObject
     */
    protected function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param MockObject $definition
     * @param string $method
     * @param mixed $returnValue
     */
    protected function setMethodReturnValue(MockObject $definition, $method, $returnValue = null)
    {
        $definition
            ->expects($this->any())
            ->method($method)
            ->will($this->returnValue($returnValue));
    }
}
