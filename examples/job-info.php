<?php

use Detail\Blitline\Client\BlitlineClient;

$config = require 'bootstrap.php';

$jobId = isset($_GET['job_id']) ? $_GET['job_id'] : null;

if (!$jobId) {
    throw new RuntimeException('Missing or invalid parameter "job_id"');
}

$blitline = BlitlineClient::factory($config);

$response = $blitline->pollJob(['job_id' => $jobId]);

var_dump($response->toArray());
