<?php

namespace Detail\Blitline\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Command\CommandInterface;
use GuzzleHttp\Command\Exception\CommandException;
use GuzzleHttp\Command\Guzzle\Description as ServiceDescription;
use GuzzleHttp\Command\Guzzle\DescriptionInterface as ServiceDescriptionInterface;
use GuzzleHttp\Command\Guzzle\GuzzleClient as ServiceClient;

use Detail\Blitline\Client\Subscriber;
use Detail\Blitline\Exception;
use Detail\Blitline\Job\Definition\DefinitionInterface;
use Detail\Blitline\Job\JobBuilder;
use Detail\Blitline\Job\JobBuilderInterface;
use Detail\Blitline\Response;

/**
 * Blitline API client.
 *
 * @method Response\JobProcessed pollJob(array $params = array())
 * @method Response\JobSubmitted submitJob(mixed $job = array())
 */
class BlitlineClient extends ServiceClient
{
    const CLIENT_VERSION = '0.5.1';

    /**
     * @var JobBuilderInterface
     */
    protected $jobBuilder;

    /**
     * @param array $options
     * @param JobBuilderInterface $jobBuilder
     * @return BlitlineClient
     */
    public static function factory($options = array(), JobBuilderInterface $jobBuilder = null)
    {
        $requiredOptions = array(
            'application_id',
        );

        foreach ($requiredOptions as $optionName) {
            if (!isset($options[$optionName]) || $options[$optionName] === '') {
                throw new Exception\InvalidArgumentException(
                    sprintf('Missing required configuration option "%s"', $optionName)
                );
            }
        }

        $defaultOptions = array(
            'base_url' => 'https://api.blitline.com/',
            'defaults' => array(
                // Float describing the number of seconds to wait while trying to connect to a server.
                // 0 was the default (wait indefinitely).
                'connect_timeout' => 10,
                // Float describing the timeout of the request in seconds.
                // 0 was the default (wait indefinitely).
                'timeout' => 5, // 60 seconds, may be overridden by individual operations
            ),
        );

        // These are always applied
        $overrideOptions = array(
            'defaults' => array(
                // We're using our own error handler
                // (this disables the use of the internal HttpError subscriber)
                'exceptions' => false,
                'headers' => array(
                    'Accept' => 'application/json',
                    'User-Agent' => 'detailnet-blitline/' . self::CLIENT_VERSION,
                ),
                'query' => array(
                    'application_id' => $options['application_id'],
                )
            ),
        );

        // Apply options
        $config = array_replace_recursive($defaultOptions, $options, $overrideOptions);

        $httpClient = new HttpClient($config);
        $httpClient->getEmitter()->attach(new Subscriber\ErrorHandler());
//        $httpClient->getEmitter()->attach(new Subscriber\ExpectedContentTypeSubscriber());
//        $httpClient->getEmitter()->attach(new Subscriber\RequestOptionsSubscriber());

        $description = new ServiceDescription(require __DIR__ . '/../ServiceDescription/Blitline.php');
        $client = new static($httpClient, $description, $jobBuilder);

        return $client;
    }

    /**
     * @param HttpClientInterface $client
     * @param ServiceDescriptionInterface $description
     * @param JobBuilderInterface $jobBuilder
     */
    public function __construct(
        HttpClientInterface $client,
        ServiceDescriptionInterface $description,
        JobBuilderInterface $jobBuilder = null
    ) {
        $config = array(
//            'process' => false, // Don't use Guzzle Service's processing (we're rolling our own...)
        );

        parent::__construct($client, $description, $config);

        if ($jobBuilder !== null) {
            $this->setJobBuilder($jobBuilder);
        }

//        $emitter = $this->getEmitter();
//        $emitter->attach(
//            new Subscriber\ProcessResponse($description)
//        );

    }

    /**
     * @return JobBuilderInterface
     */
    public function getJobBuilder()
    {
        if ($this->jobBuilder === null) {
            $this->jobBuilder = new JobBuilder();
        }

        return $this->jobBuilder;
    }

    /**
     * @param JobBuilderInterface $jobBuilder
     * @return BlitlineClient
     */
    public function setJobBuilder(JobBuilderInterface $jobBuilder)
    {
        $this->jobBuilder = $jobBuilder;
        return $this;
    }

//    /**
//     * @return \Guzzle\Http\Message\RequestFactoryInterface
//     */
//    public function getRequestFactory()
//    {
//        return $this->requestFactory;
//    }

    /**
     * @param CommandInterface $command
     * @return \GuzzleHttp\Ring\Future\FutureInterface|mixed|null
     */
    public function execute(CommandInterface $command)
    {
        // It seems we can't intercept Guzzle's request exceptions through the event system...
        // e.g. when http://api.blitline.com/ is unreachable or the request times out.
        try {
            return parent::execute($command);
        } catch (CommandException $e) {
            throw new Exception\RuntimeException(
                sprintf('Request failed: %s', $e->getMessage()),
                0,
                $e
            );
        }
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
}
