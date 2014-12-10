<?php

namespace Detail\Blitline\Client;

use Detail\Blitline\Job\JobBuilder;
use Detail\Blitline\Job\JobBuilderInterface;
use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

use Detail\Blitline\Client\Listener\ExpectedContentTypeListener;
use Detail\Blitline\Exception\InvalidArgumentException;

/**
 * Blitline API client.
 *
 * @method array pollJob(array $args = array())
 * @method array postJob(array $args = array())
 */
class BlitlineClient extends Client
{
    const CLIENT_VERSION = '0.2.0';

    /** @var  JobBuilderInterface */
    protected $jobBuilder;

    public static function factory($options = array())
    {
        $defaultOptions = array('base_url' => 'https://api.blitline.com/');

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

        $client = new self($config->get('base_url'), $config);
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

        $client->getEventDispatcher()->addSubscriber(new ExpectedContentTypeListener());

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
     */
    public function setJobBuilder(JobBuilderInterface $jobBuilder)
    {
        $this->jobBuilder = $jobBuilder;
    }

    public function __construct($baseUrl = '', $config = null, JobBuilderInterface $jobBuilder = null)
    {
        parent::__construct($baseUrl, $config);

        if ($jobBuilder !== null) {
            $this->setJobBuilder($jobBuilder);
        }
    }
}
