<?php

namespace DetailTest\Blitline\Job;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\Blitline\Exception;
use Detail\Blitline\Job\Definition;
use Detail\Blitline\Job\JobBuilder;

class JobBuilderTest extends TestCase
{
    /**
     * @var JobBuilder
     */
    protected $jobBuilder;

    public function provideJobDefinitionDefaultOptions()
    {
        return [
            [
                [
                ],
                [
                ],
            ],
            [
                [
                    'job.src' => 'job.src',
                    'function.name' => 'function.name'
                ],
                [
                    'src' => 'job.src',
                ],
            ],
            [
                [
                    'src' => 'src',
                    'job' => 'job',
                    'function' => 'function',
                    'job.' => 'job.',
                    'function.' => 'function.',
                ],
                [
                ],
            ],
        ];
    }

    public function provideFunctionDefinitionDefaultOptions()
    {
        return [
            [
                [
                ],
                [
                ],
            ],
            [
                [
                    'job.src' => 'job.src',
                    'function.name' => 'function.name'
                ],
                [
                    'name' => 'function.name',
                ],
            ],
            [
                [
                    'src' => 'src',
                    'job' => 'job',
                    'function' => 'function',
                    'job.' => 'job.',
                    'function.' => 'function.',
                ],
                [
                ],
            ],
        ];
    }

    protected function setUp()
    {
        $this->jobBuilder = new JobBuilder();
    }

    public function testJobClassCanBeSet()
    {
        $this->assertEquals(
            Definition\JobDefinition::CLASS,
            $this->jobBuilder->getJobClass()
        );

        $class = 'CustomJobDefinitionClass';

        $this->jobBuilder->setJobClass($class);

        $this->assertEquals($class, $this->jobBuilder->getJobClass());
    }

    public function testSourceClassCanBeSet()
    {
        $this->assertEquals(
            Definition\SourceDefinition::CLASS,
            $this->jobBuilder->getSourceClass()
        );

        $class = 'CustomSourceDefinitionClass';

        $this->jobBuilder->setSourceClass($class);

        $this->assertEquals($class, $this->jobBuilder->getSourceClass());
    }

    public function testFunctionClassCanBeSet()
    {
        $this->assertEquals(
            Definition\FunctionDefinition::CLASS,
            $this->jobBuilder->getFunctionClass()
        );

        $class = 'CustomFunctionDefinitionClass';

        $this->jobBuilder->setFunctionClass($class);

        $this->assertEquals($class, $this->jobBuilder->getFunctionClass());
    }

    public function testDefaultOptionCanBeSet()
    {
        $key          = 'key';
        $value        = 'value';
        $defaultValue = 'defaultValue';

        $this->assertNull($this->jobBuilder->getDefaultOption($key));

        $this->jobBuilder->setDefaultOption($key, $value);

        $this->assertEquals($value, $this->jobBuilder->getDefaultOption($key));
        $this->assertEquals(
            $defaultValue,
            $this->jobBuilder->getDefaultOption('non-existing-key', $defaultValue)
        );
    }

    public function testDefaultOptionsCanBeSet()
    {
        $options = ['key' => 'value'];

        $this->assertEmpty($this->jobBuilder->getDefaultOptions());

        $this->jobBuilder->setDefaultOptions($options);

        $this->assertEquals($options, $this->jobBuilder->getDefaultOptions());
    }

    public function testCanCreateJobDefinition()
    {
        $job = $this->jobBuilder->createJob();

        $this->assertInstanceOf(Definition\JobDefinition::CLASS, $job);
    }

    public function testCanCreateSourceDefinition()
    {
        $source = $this->jobBuilder->createSource();

        $this->assertInstanceOf(Definition\SourceDefinition::CLASS, $source);
    }

    public function testCanCreateFunctionDefinition()
    {
        $function = $this->jobBuilder->createFunction();

        $this->assertInstanceOf(Definition\FunctionDefinition::CLASS, $function);
    }

    public function testDefinitionCreationWithMissingClassThrowsException()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);

        $this->jobBuilder->setJobClass('NonExistingDefinitionClass');
        $this->jobBuilder->createJob();
    }

    public function testDefinitionCreationWithInvalidInterfaceThrowsException()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);

        // Using existing class which doesn't implement Detail\Blitline\Job\JobBuilder\Definition\DefinitionInterface
        $this->jobBuilder->setJobClass(JobBuilder::CLASS);
        $this->jobBuilder->createJob();
    }

    /**
     * @param array $options
     * @param array $expectedOptions
     * @dataProvider provideJobDefinitionDefaultOptions
     */
    public function testDefaultOptionsCanBeGetForJobDefinition(array $options, array $expectedOptions)
    {
        $this->jobBuilder->setDefaultOptions($options);

        $jobDefinition = $this->jobBuilder->createJob();

        $this->assertEquals($expectedOptions, $this->jobBuilder->getDefaultOptions($jobDefinition));
    }

    /**
     * @param array $options
     * @param array $expectedOptions
     * @dataProvider provideFunctionDefinitionDefaultOptions
     */
    public function testDefaultOptionsCanBeGetForFunctionDefinition(array $options, array $expectedOptions)
    {
        $this->jobBuilder->setDefaultOptions($options);

        $functionDefinition = $this->jobBuilder->createFunction();

        $this->assertEquals($expectedOptions, $this->jobBuilder->getDefaultOptions($functionDefinition));
    }

    public function testDefaultOptionsAreEmptyUnknownDefinition()
    {
        $this->jobBuilder->setDefaultOptions(
            ['job.src' => 'job.src', 'function.name' => 'function.name']
        );

        $definition = $this->getMock(Definition\DefinitionInterface::CLASS);

        /** @var Definition\DefinitionInterface $definition */

        $this->assertEquals([], $this->jobBuilder->getDefaultOptions($definition));
    }
}
