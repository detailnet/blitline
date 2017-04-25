<?php

namespace Detail\Blitline\Job\Source;

use Detail\Blitline\Exception;

class AwsS3Source extends BaseSource
{
    /**
     * @var string
     */
    protected $region = 'eu-west-1';

    /**
     * @var string
     */
    protected $bucket;

    /**
     * @var string
     */
    protected $key;

    /**
     * @param string|BaseSource $url
     * @return static
     * @throws Exception\InvalidArgumentException if provided URL is not of an valid AWS S3 bucket
     */
    public static function fromUrl($url)
    {
        $urlParts = parse_url($url instanceof BaseSource ? $url->getUrl() : $url);
        @list($bucket, $s3region, $domain) = explode('.', $urlParts['host'], 3);

        if ($urlParts['scheme'] !== 'https') {
            throw new Exception\InvalidArgumentException('Provided URL is not of an AWS s3 bucket: Invalid scheme');
        }

        if (substr($s3region, 0, 3) !== 's3-') {
            throw new Exception\InvalidArgumentException('Provided URL is not of an AWS s3 bucket: Invalid region specification');
        }

        if ($domain !== 'amazonaws.com') {
            throw new Exception\InvalidArgumentException('Provided URL is not of an AWS s3 bucket: Invalid domain postfix');
        }

        if (strlen($urlParts['path']) < 2 || substr($urlParts['path'], -1) === '/') {
            throw new Exception\InvalidArgumentException('Provided URL is not of an AWS s3 bucket: Invalid path');
        }

        return new static(
            $bucket,
            ltrim($urlParts['path'], '/'),
            substr($s3region, 3)
        );
    }

    /**
     * @param string $bucket
     * @param string $key
     * @param string|null $region
     */
    public function __construct($bucket, $key, $region = null)
    {
        parent::__construct(static::TYPE_S3);

        $this->bucket = $bucket;
        $this->key = $key;

        if ($region !== null) {
            $this->region = $region;
        }
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return sprintf(
            'https://%s.s3-%s.amazonaws.com/%s',
            $this->getBucket(),
            $this->getRegion(),
            $this->getKey()
        );
    }
}
