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
        return array(
            array(
                array(
                ),
                array(
                ),
            ),
            array(
                array(
                    'job.src' => 'job.src',
                    'function.name' => 'function.name'
                ),
                array(
                    'src' => 'job.src',
                ),
            ),
            array(
                array(
                    'src' => 'src',
                    'job' => 'job',
                    'function' => 'function',
                    'job.' => 'job.',
                    'function.' => 'function.',
                ),
                array(
                ),
            ),
        );
    }

    public function provideFunctionDefinitionDefaultOptions()
    {
        return array(
            array(
                array(
                ),
                array(
                ),
            ),
            array(
                array(
                    'job.src' => 'job.src',
                    'function.name' => 'function.name'
                ),
                array(
                    'name' => 'function.name',
                ),
            ),
            array(
                array(
                    'src' => 'src',
                    'job' => 'job',
                    'function' => 'function',
                    'job.' => 'job.',
                    'function.' => 'function.',
                ),
                array(
                ),
            ),
        );
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
        $options = array('key' => 'value');

        $this->assertEmpty($this->jobBuilder->getDefaultOptions());

        $this->jobBuilder->setDefaultOptions($options);

        $this->assertEquals($options, $this->jobBuilder->getDefaultOptions());
    }

    public function testCanCreateJobDefinition()
    {
        $job = $this->jobBuilder->createJob();

        $this->assertInstanceOf(Definition\JobDefinition::CLASS, $job);
    }

    public function testCanCreateFunctionDefinition()
    {
        $job = $this->jobBuilder->createFunction();

        $this->assertInstanceOf(Definition\FunctionDefinition::CLASS, $job);
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
            array('job.src' => 'job.src', 'function.name' => 'function.name')
        );

        $definition = $this->getMock(Definition\DefinitionInterface::CLASS);

        /** @var Definition\DefinitionInterface $definition */

        $this->assertEquals(array(), $this->jobBuilder->getDefaultOptions($definition));
    }
}
