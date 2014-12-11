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

    public function provideDefaultOptions()
    {
        return array(
            array(
                array('key' => 'value'),
            ),
        );
    }

    protected function setUp()
    {
        $this->jobBuilder = new JobBuilder();
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
        /** @todo Implement */
    }

    public function testDefinitionCreationWithInvalidInterfaceThrowsException()
    {
        /** @todo Implement */
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

    /**
     * @param array $options
     * @dataProvider provideDefaultOptions
     */
    public function testDefaultOptionsAreApplied(array $options)
    {
        /** @todo Implement */
    }
}
