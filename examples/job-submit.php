<?php

use Detail\Blitline\Client\BlitlineClient;

$config = require 'bootstrap.php';

$imageUrl = isset($_GET['image_url']) ? $_GET['image_url'] : null;

if (!$imageUrl) {
    throw new RuntimeException('Missing or invalid parameter "image_url"');
}

$getConfig = function($optionName) use ($config) {
    if (!isset($config[$optionName])) {
        throw new RuntimeException(sprintf('Missing configuration option "%s"', $optionName));
    }

    return $config[$optionName];
};

$imageSize = isset($_GET['image_size']) ? $_GET['image_size'] : 200;
$image = new SplFileInfo($imageUrl);
$imageName = $image->getBasename();

$blitline = BlitlineClient::factory($config);

/** @var \Detail\Blitline\Job\JobBuilder $jobBuilder */
$jobBuilder = $blitline->getJobBuilder();
$jobBuilder->setDefaultOption(
    'function.save',
    [
        's3_destination' => [
            'bucket' => $getConfig('s3bucket'),
        ],
    ]
);

$job = $jobBuilder->createJob()
    ->setSource($imageUrl)
    ->addFunction(
        $jobBuilder->createFunction()
            ->setName('resize_to_fit')
            ->setParams(
                [
                    'width' => $imageSize,
                    'height' => $imageSize,
                    'only_shrink_larger' => true, // Don't upscale image
                ]
            )
            ->setSaveOptions(
                [
                    'image_identifier' => $imageName,
                    's3_destination' => [
//                        'bucket' => $getConfig('s3bucket'),
                        'key' => $getConfig('s3prefix') . '/' . $imageName . '-' . $imageSize . '_blitline.jpg',
                    ],
                ]
            )
    );

if (isset($config['version'])) {
    $job->setVersion($config['version']);
}

$response = $blitline->submitJob($job);

if ($response->hasErrors()) {
    var_dump($response->getErrors());
} else {
    var_dump(
        [
            'job_id' => $response->getJobId(),
            'images' => $response->getImages(),
        ]
    );
}


