<?php

use Detail\Blitline\Client\BlitlineClient;

$config = require 'bootstrap.php';

$blitline = BlitlineClient::factory($config);

/** @todo Implement */
$job = array();

$response = $blitline->postJob($job);

var_dump($response);
