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
            [
                'subA' => 'subB'
            ]
        );

        return [
            [
                [
                ],
                [
                ],
                [
                ],
            ],
            [
                [
                    'a' => 'b',
                    'c' => [
                        'd' => 'e',
                        'f' => [
                            'g' => 'h',
                        ],
                    ],
                ],
                [
                    'a' => 'b1',
                    'c' => [
                        'd' => 'e1',
                        'f' => [
                            'i' => 'j',
                        ],
                        'k' => 'l',
                    ],
                    'm' => $subDefinition,
                ],
                [
                    'a' => 'b1',
                    'c' => [
                        'd' => 'e1',
                        'f' => [
                            'g' => 'h',
                            'i' => 'j',
                        ],
                        'k' => 'l',
                    ],
                    'm' => [
                        'subA' => 'subB'
                    ],
                ],
            ],
        ];
    }

    public function provideOption()
    {
        return [
            [
                'key',
                'defaultValue',
                'initialValue',
                'value',
                'value',
            ],
            [
                'key',
                'defaultValue',
                [
                    'a' => 'b',
                    'c' => 'd',
                ],
                [
                    'a' => 'b1',
                    'e' => 'f',
                ],
                [
                    'a' => 'b1',
                    'c' => 'd',
                    'e' => 'f',
                ],
            ],
        ];
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
        $this->assertEquals([], $this->definition->getOptions());
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
