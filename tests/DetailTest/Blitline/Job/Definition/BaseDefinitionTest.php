<?php

namespace DetailTest\Blitline\Job\Definition;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\Blitline\Job\Definition\BaseDefinition;

class BaseDefinitionTest extends TestCase
{
    /**
     * @var BaseDefinition
     */
    protected $definition;

    public function provideOptions()
    {
        $subDefinition = $this->getMockForAbstractClass(
            'Detail\Blitline\Job\Definition\BaseDefinition'
        );

        /** @var BaseDefinition $subDefinition */
        $subDefinition->setOptions(
            array(
                'subA' => 'subB'
            )
        );

        return array(
            array(
                array(
                ),
                array(
                ),
                array(
                ),
            ),
            array(
                array(
                    'a' => 'b',
                    'c' => array(
                        'd' => 'e',
                        'f' => array(
                            'g' => 'h',
                        ),
                    ),
                ),
                array(
                    'a' => 'b1',
                    'c' => array(
                        'd' => 'e1',
                        'f' => array(
                            'i' => 'j',
                        ),
                        'k' => 'l',
                    ),
                    'm' => $subDefinition,
                ),
                array(
                    'a' => 'b1',
                    'c' => array(
                        'd' => 'e1',
                        'f' => array(
                            'g' => 'h',
                            'i' => 'j',
                        ),
                        'k' => 'l',
                    ),
                    'm' => array(
                        'subA' => 'subB'
                    ),
                ),
            ),
        );
    }

    public function provideOption()
    {
        return array(
            array(
                'key',
                'defaultValue',
                'initialValue',
                'value',
                'value',
            ),
            array(
                'key',
                'defaultValue',
                array(
                    'a' => 'b',
                    'c' => 'd',
                ),
                array(
                    'a' => 'b1',
                    'e' => 'f',
                ),
                array(
                    'a' => 'b1',
                    'c' => 'd',
                    'e' => 'f',
                ),
            ),
        );
    }

    protected function setUp()
    {
        $this->definition = $this->getMockForAbstractClass(
            'Detail\Blitline\Job\Definition\BaseDefinition'
        );
    }

    /**
     * @param array $initialOptions
     * @param array $options
     * @param array $expectedOptions
     * @dataProvider provideOptions
     */
    public function testOptionsCanBeApplied(array $initialOptions, array $options, array $expectedOptions)
    {
        $this->assertEquals(array(), $this->definition->getOptions());
        $this->assertEquals($this->definition, $this->definition->setOptions($initialOptions));
        $this->assertEquals($initialOptions, $this->definition->getOptions());
        $this->assertEquals($this->definition, $this->definition->applyOptions($options));
        $this->assertEquals($expectedOptions, $this->definition->toArray());
    }

    /**
     * @param string $key
     * @param string $defaultValue
     * @param mixed $value
     * @param string $initialValue
     * @param mixed $expectedValue
     * @dataProvider provideOption
     */
    public function testOptionCanBeSet($key, $defaultValue, $initialValue, $value, $expectedValue)
    {
        $this->assertNull($this->definition->getOption($key));
        $this->assertEquals($defaultValue, $this->definition->getOption($key, $defaultValue));
        $this->assertEquals($this->definition, $this->definition->setOption($key, $initialValue));
        $this->assertEquals($initialValue, $this->definition->getOption($key));
        $this->assertEquals($this->definition, $this->definition->setOption($key, $value));
        $this->assertEquals($expectedValue, $this->definition->getOption($key));
    }
}
