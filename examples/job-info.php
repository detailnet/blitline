<?php

use Detail\Blitline\Client\BlitlineClient;

$config = require 'bootstrap.php';

$jobId = isset($_GET['jobId']) ? $_GET['jobId'] : null;

if (!$jobId) {
    throw new RuntimeException('Missing or invalid parameter "jobId"');
}

$blitline = BlitlineClient::factory($config);

$response = $blitline->pollJob(array('jobId' => $jobId));

var_dump($response);
