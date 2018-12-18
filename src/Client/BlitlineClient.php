<?php

namespace Detail\Blitline\Client;

use GuzzleHttp\Client as HttpClient;
//use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Command\CommandInterface;
use GuzzleHttp\Command\Guzzle\Description as ServiceDescription;
//use GuzzleHttp\Command\Guzzle\DescriptionInterface as ServiceDescriptionInterface;
use GuzzleHttp\Command\Guzzle\GuzzleClient as ServiceClient;

use Detail\Blitline\Client\Response;
//use Detail\Blitline\Client\Subscriber;
use Detail\Blitline\Exception;
use Detail\Blitline\Job\Definition\DefinitionInterface;
use Detail\Blitline\Job\JobBuilder;
use Detail\Blitline\Job\JobBuilderInterface;

/**
 * Blitline API client
 *
 * @method Response\JobProcessed pollJob(array $params = [])
 * @method Response\JobSubmitted submitJob(mixed $job = [])
 */
class BlitlineClient extends ServiceClient
{
    const CLIENT_VERSION = '1.0.0';

    /**
     * @var JobBuilderInterface
     */
    private $jobBuilder;

    public static function factory(array $options = [], ?JobBuilderInterface $jobBuilder = null): BlitlineClient
    {
        $requiredOptions = [
            'application_id',
        ];

        foreach ($requiredOptions as $optionName) {
            if (!isset($options[$optionName]) || $options[$optionName] === '') {
                throw new Exception\RuntimeException(
                    sprintf('Missing required configuration option "%s"', $optionName)
                );
            }
        }

        $defaultOptions = [
            'base_uri' => 'https://api.blitline.com/',
            // Float describing the number of seconds to wait while trying to connect to a server.
            // 0 was the default (wait indefinitely).
            'connect_timeout' => 10,
            // Float describing the timeout of the request in seconds.
            // 0 was the default (wait indefinitely).
            'timeout' => 60, // 60 seconds, may be overridden by individual operations
        ];

        // These are always applied
        $overrideOptions = [
            // We're using our own error handling middleware,
            // so disable throwing exceptions on HTTP protocol errors (i.e., 4xx and 5xx responses).
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'detailnet-blitline/' . self::CLIENT_VERSION,
            ],
            'query' => [
                'application_id' => $options['application_id'],
            ]
        ];

        // Apply options
        $config = array_replace_recursive($defaultOptions, $options, $overrideOptions);

        $httpClient = new HttpClient($config);
//        $httpClient->getEmitter()->attach(new Subscriber\Http\ProcessError());

        $description = new ServiceDescription(require __DIR__ . '/ServiceDescription/Blitline.php');
        $deserializer = new Deserializer($description);
        $client = new static($httpClient, $description, null, $deserializer);

        if ($jobBuilder !== null) {
            $client->setJobBuilder($jobBuilder);
        }

        return $client;
    }

    public function getBlitlineApplicationId(): ?string
    {
        return $this->getHttpClient()->getConfig('query')['application_id'];
    }

    public function getBlitlineUrl(): ?string
    {
        return $this->getHttpClient()->getConfig('base_uri');
    }

    public function getJobBuilder(): JobBuilderInterface
    {
        if ($this->jobBuilder === null) {
            $this->jobBuilder = new JobBuilder();
        }

        return $this->jobBuilder;
    }

    public function setJobBuilder(JobBuilderInterface $jobBuilder): void
    {
        $this->jobBuilder = $jobBuilder;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        if (isset($args[0]) && $args[0] instanceof DefinitionInterface) {
            /** @var DefinitionInterface $definition */
            $definition = $args[0];
            $args[0] = $definition->toArray();
        }

        return parent::__call($method, $args);
    }

    /**
     * @param string $name
     * @param array $params
     * @return CommandInterface
     */
    public function getCommand($name, array $params = [])
    {
        $command = parent::getCommand($name, $params);
        $requestOptions = $this->getRequestOptions($command);

        if ($requestOptions !== null) {
            $command['@http'] = $requestOptions;
        }

        return $command;
    }

    private function getRequestOptions(CommandInterface $command): ?array
    {
        $operation = $this->getDescription()->getOperation($command->getName());
        $requestOptions = $operation->getData('http');

        return is_array($requestOptions) ? $requestOptions : null;
    }
}
