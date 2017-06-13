<?php

use Detail\Blitline\Client\BlitlineClient;
use Detail\Blitline\Job\Source\AwsS3Source;

$config = require 'bootstrap.php';

$imageUrl = isset($_GET['image_url']) ? $_GET['image_url'] : null;
$imageKey = isset($_GET['image_key']) ? $_GET['image_key'] : null;
$getConfig = function($optionName) use ($config) {
    if (!isset($config[$optionName])) {
        throw new RuntimeException(sprintf('Missing configuration option "%s"', $optionName));
    }

    return $config[$optionName];
};

$imageSize = isset($_GET['image_size']) ? $_GET['image_size'] : 200;
$blitline = BlitlineClient::factory($config);

/** @var \Detail\Blitline\Job\JobBuilder $jobBuilder */
$jobBuilder = $blitline->getJobBuilder();
$jobBuilder->setDefaultOption(
    'function.save',
    array(
        's3_destination' => array(
            'bucket' => $getConfig('s3bucket'),
        ),
    )
);

if ($imageUrl !== null) {
    $image = new SplFileInfo($imageUrl);
    $source = $imageUrl;
} else if ($imageKey !== null) {
    $image = new SplFileInfo($imageKey);
    $source = $jobBuilder->createSource()
        ->setBucket($getConfig('s3bucket'))
        ->setKey($imageKey);
} else {
    throw new RuntimeException('Missing parameter "image_url" or "image_key" (only one can be set)');
}

$imageName = $image->getBasename();

$job = $jobBuilder->createJob()
    ->setSource($source)
    ->addFunction(
        $jobBuilder->createFunction()
            ->setName('resize_to_fit')
            ->setParams(
                array(
                    'width' => $imageSize,
                    'height' => $imageSize,
                    'only_shrink_larger' => true, // Don't upscale image
                )
            )
            ->setSaveOptions(
                array(
                    'image_identifier' => $imageName,
                    's3_destination' => array(
//                        'bucket' => $getConfig('s3bucket'),
                        'key' => $getConfig('s3prefix') . '/' . $imageName . '-' . $imageSize . '_blitline.jpg',
                    ),
                )
            )
    );

if (isset($config['version'])) {
    $job->setVersion($config['version']);
}

$response = $blitline->submitJob($job);

var_dump($response->getResult());
