<?php

use Detail\Blitline\Client\BlitlineClient;

$config = require 'bootstrap.php';

$imageUrl = isset($_GET['imageUrl']) ? $_GET['imageUrl'] : null;

if (!$imageUrl) {
    throw new RuntimeException('Missing or invalid parameter "imageUrl"');
}

$imageSize = isset($_GET['imageSize']) ? $_GET['imageSize'] : 200;
$image = new SplFileInfo($imageUrl);
$imageName = $image->getBasename();

$getConfig = function($optionName) use ($config) {
    if (!isset($config[$optionName])) {
        throw new RuntimeException(sprintf('Missing configuration option "%s"', $optionName));
    }

    return $config[$optionName];
};

$blitline = BlitlineClient::factory($config);

$job = array(
    'src' => $imageUrl,
    'v' => isset($config['version']) ? $config['version'] : '1.21',
    'functions' => array(
        array(
            'name' => 'resize_to_fit',
            'params' => array(
                'width' => $imageSize,
                'height' => $imageSize,
                'only_shrink_larger' => true, // Don't upscale image
            ),
            'save' => array(
                'image_identifier' => $imageName,
                's3_destination' => array(
                    'bucket' => $getConfig('s3bucket'),
                    'key' => $getConfig('s3path') . '/' . $imageName . '-blitline.jpg',
                ),
            ),
        ),
    ),
);

$response = $blitline->postJob($job);

var_dump($response);
