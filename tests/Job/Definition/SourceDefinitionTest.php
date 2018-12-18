<?php

namespace DetailTest\Blitline\Job\Definition;

use Detail\Blitline\Job\Definition\SourceDefinition;

class SourceDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass(): string
    {
        return SourceDefinition::CLASS;
    }

    public function testNameCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $name = 's3';

        $this->setMethodReturnValue($definition, 'getOption', $name);

        /** @var SourceDefinition $definition */

        $this->assertEquals($definition, $definition->setName($name));
        $this->assertEquals($name, $definition->getName());
    }

    public function testBucketCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $bucket = 'my-bucket';

        $this->setMethodReturnValue($definition, 'getOption', $bucket);

        /** @var SourceDefinition $definition */

        $this->assertEquals($definition, $definition->setBucket($bucket));
        $this->assertEquals($bucket, $definition->getBucket());
    }

    public function testKeyCanBeSet(): void
    {
        $definition = $this->getDefinition();
        $key = 'path-to-file/filename.ext';

        $this->setMethodReturnValue($definition, 'getOption', $key);

        /** @var SourceDefinition $definition */

        $this->assertEquals($definition, $definition->setKey($key));
        $this->assertEquals($key, $definition->getKey());
    }
}
