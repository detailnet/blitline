<?php

namespace DetailTest\Blitline\Job;

use PHPUnit_Framework_TestCase as TestCase;

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
            'Detail\Blitline\Job\Definition\JobDefinition',
            $this->jobBuilder->getJobClass()
        );

        $class = 'CustomJobDefinitionClass';

        $this->jobBuilder->setJobClass($class);

        $this->assertEquals($class, $this->jobBuilder->getJobClass());
    }

    public function testFunctionClassCanBeSet()
    {
        $this->assertEquals(
            'Detail\Blitline\Job\Definition\FunctionDefinition',
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

        $this->assertInstanceOf('Detail\Blitline\Job\Definition\JobDefinition', $job);
    }

    public function testCanCreateFunctionDefinition()
    {
        $job = $this->jobBuilder->createFunction();

        $this->assertInstanceOf('Detail\Blitline\Job\Definition\FunctionDefinition', $job);
    }

    public function testDefinitionCreationWithMissingClassThrowsException()
    {
        $this->setExpectedException('Detail\Blitline\Exception\RuntimeException');

        $this->jobBuilder->setJobClass('NonExistingDefinitionClass');
        $this->jobBuilder->createJob();
    }

    public function testDefinitionCreationWithInvalidInterfaceThrowsException()
    {
        $this->setExpectedException('Detail\Blitline\Exception\RuntimeException');

        // Using existing class which doesn't implement Detail\Blitline\Job\JobBuilder\Definition\DefinitionInterface
        $this->jobBuilder->setJobClass('Detail\Blitline\Job\JobBuilder');
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

        $definition = $this->getMock('Detail\Blitline\Job\Definition\DefinitionInterface');

        /** @var \Detail\Blitline\Job\Definition\DefinitionInterface $definition */

        $this->assertEquals(array(), $this->jobBuilder->getDefaultOptions($definition));
    }
}
