<?php

namespace Detail\Blitline\Job\Definition;

interface JobDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $url
     * @return JobDefinitionInterface
     * @deprecated Will be removed in future versions, use 'setSource' instead
     */
    public function setSourceUrl($url);

    /**
     * @return string
     * @deprecated Will be removed in future versions, use 'getSource' instead
     */
    public function getSourceUrl();

    /**
     * @param SourceInterface $src
     * @return JobDefinitionInterface
     */
    public function setSource(SourceInterface $src);

    /**
     * @return SourceInterface
     */
    public function getSource();

    /**
     * @param string $url
     * @return JobDefinitionInterface
     */
    public function setPostbackUrl($url);

    /**
     * @return string
     */
    public function getPostbackUrl();

    /**
     * @param string $version
     * @return JobDefinitionInterface
     */
    public function setVersion($version);

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @param array|FunctionDefinitionInterface[] $functions
     * @return JobDefinitionInterface
     */
    public function setFunctions(array $functions);

    /**
     * @return array|FunctionDefinitionInterface[]
     */
    public function getFunctions();

    /**
     * @param array|FunctionDefinitionInterface $function
     * @return JobDefinitionInterface
     */
    public function addFunction($function);
}
