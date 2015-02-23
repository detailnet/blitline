<?php

namespace Detail\Blitline\Client;

use Guzzle\Common\Collection;
use Guzzle\Http\Exception as GuzzleHttpException;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

use Detail\Blitline\Client\Subscriber\ErrorHandlerSubscriber;
use Detail\Blitline\Client\Subscriber\ExpectedContentTypeSubscriber;
use Detail\Blitline\Client\Subscriber\RequestOptionsSubscriber;
use Detail\Blitline\Exception\InvalidArgumentException;
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
class BlitlineClient extends Client
{
    const CLIENT_VERSION = '0.4.2';

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
        $defaultOptions = array(
            'base_url' => 'https://api.blitline.com/',
            'request.options' => array(
                // Float describing the number of seconds to wait while trying to connect to a server.
                // 0 was the default (wait indefinitely).
                'connect_timeout' => 10,
                // Float describing the timeout of the request in seconds.
                // 0 was the default (wait indefinitely).
                'timeout' => 60, // 60 seconds, may be overridden by individual operations
            ),
        );

        $requiredOptions = array(
            'application_id',
        );

        foreach ($requiredOptions as $optionName) {
            if (!isset($options[$optionName]) || $options[$optionName] === '') {
                throw new InvalidArgumentException(
                    sprintf('Missing required configuration option "%s"', $optionName)
                );
            }
        }

        $config = Collection::fromConfig($options, $defaultOptions);

        $client = new self($config->get('base_url'), $config, $jobBuilder);
        $client->setDefaultOption(
            'query',
            array(
                'application_id' => $config['application_id'],
            )
        );
        $client->setDefaultOption(
            'headers',
            array(
                'Accept' => 'application/json',
            )
        );

        $client->setDescription(
            ServiceDescription::factory(__DIR__ . '/../ServiceDescription/Blitline.php')
        );
        $client->setUserAgent('detailnet-blitline/' . self::CLIENT_VERSION, true);

        $client->getEventDispatcher()->addSubscriber(new ErrorHandlerSubscriber());
        $client->getEventDispatcher()->addSubscriber(new ExpectedContentTypeSubscriber());
        $client->getEventDispatcher()->addSubscriber(new RequestOptionsSubscriber());

        return $client;
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

    /**
     * @return \Guzzle\Http\Message\RequestFactoryInterface
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param string $baseUrl
     * @param array|Collection $config
     * @param JobBuilderInterface $jobBuilder
     */
    public function __construct($baseUrl = '', $config = null, JobBuilderInterface $jobBuilder = null)
    {
        parent::__construct($baseUrl, $config);

        if ($jobBuilder !== null) {
            $this->setJobBuilder($jobBuilder);
        }
    }

    public function execute($command)
    {
        // It seems we can't intercept Guzzle's request exceptions through the event system...
        // e.g. when http://api.blitlineee.com/ is unreachable or the request times out.
        try {
            return parent::execute($command);
        } catch (GuzzleHttpException\RequestException $e) {
            // We want to throw our own exceptions so that consumers of the library know which
            // exceptions to handle.
            $this->dispatch(
                'request.exception',
                array(
                    'command'   => $command,
                    'request'   => $command->getRequest(),
                    'exception' => $e,
                )
            );

            // Should not be needed as our error handler will throw an exception...
            return array();
        }
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (isset($args[0]) && $args[0] instanceof DefinitionInterface) {
            /** @var DefinitionInterface $definition */
            $definition = $args[0];
            $args[0] = $definition->toArray();
        }

        return parent::__call($method, $args);
    }
}
